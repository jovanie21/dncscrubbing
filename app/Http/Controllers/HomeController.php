<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DncList;
use App\Models\TokenExpiery;
use App\Models\UserDncList;
use App\Models\AdminUpload;
use App\Models\AdminScrubUpload;
use App\Models\ScrubUpload;
use App\Models\TempScrubAdmin;
use App\Models\TempScrubUser;
use App\Models\Contact;
use Validator;
use useDailyFiles;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Zip;
use Auth;
use Response;
use App\Jobs\DncUploadJob;

class HomeController extends Controller
{

  public function index()
  {
    return view('home');
  }





  public function checknumber(Request $request, $token)
  {
    $list_type = 'all';
    $number = $request->phone;
    if (isset($request->list_type)) {
      if ((strtolower($request->list_type) == 'federal') || (strtolower($request->list_type) == 'litigator') || (strtolower($request->list_type) == 'internal') || (strtolower($request->list_type) == 'wireless') || (strtolower($request->list_type) == 'all')) {
        $list_type = strtolower($request->list_type);
      }
    }
    $verify = TokenExpiery::where('token', $token)->where('status', '1')->first();

    if (empty($verify)) {
      return $this->sendError('sorry You are not Authenicated');
    } else {
      date_default_timezone_set('Asia/Calcutta');
      $cdate  = date("Y-m-d") . ' ' . date('h:i:s');

      if (is_null($number) || strlen($number) != 10) {
        return $this->sendError('Data is Incomplete');
      }

      $usernumber = UserDncList::where('user_id', $verify->user_id)->where('phone_no', $number)->first();
      if ($usernumber) {
        $is_client_dnc = 'true';
      } else {
        $is_client_dnc = 'false';
      }

      /*
    $numberdetail = DncList::where('phone_no',$number)->orWhere('federal','yes')->orWhere('litigator','yes')->orWhere('internal','yes')->orWhere('wireless','yes')->select([
        DB::raw('phone_no AS phone_no'),
        DB::raw('federal AS federal'),
        DB::raw('litigator AS litigator'),
        DB::raw('internal AS internal'),
        DB::raw('wireless AS wireless'),
    ])->first();
*/
      if ($list_type == 'all') {
        $numberdetail = DB::table('dnc_lists')
          ->where('phone_no', '=', $number)
          ->Where(function ($query) {
            $query->orwhere('federal', '=', 'yes')
              ->orwhere('litigator', '=', 'yes')
              ->orwhere('internal', '=', 'yes')
              ->orwhere('wireless', '=', 'yes');
          })
          ->first();
      } else {
        $numberdetail = DB::table('dnc_lists')
          ->where('phone_no', '=', $number)
          ->where(trim($list_type), '=', 'yes')
          ->first();
      }

      /*if(is_null($numberdetail)){
            return $this->sendError('Number not found');
          }*/
      if ($numberdetail || $usernumber) {
        $is_dnc = 'yes';
      } else {
        $is_dnc = 'no';
      }



      $code = 100;
      $message = "Successfully Fetched";
      if ($numberdetail) {


        $values = [
          "is_dnc" => $is_dnc,
          "is_faderal" => $numberdetail->federal == "yes" ? "true" : "false",
          "is_litiger" => $numberdetail->litigator == "yes" ? "true" : "false",
          "is_internal" => $numberdetail->internal == "yes" ? "true" : "false",
          "is_wireless" => $numberdetail->wireless == "yes" ? "true" : "false",
          'is_client_dnc' => $is_client_dnc
        ];
      } else {
        $values = [
          "is_dnc" => $is_dnc,
          "is_faderal" => "false",
          "is_litiger" => "false",
          "is_internal" => "false",
          "is_wireless" => "false",
          'is_client_dnc' => $is_client_dnc
        ];
      }
      $response = $usernumber;

      $result = $is_dnc;


      // $response = [
      //     'success' => '1',
      //     'message' => "Data Successfully",
      // ];
      return response()->json($result, 200);
    }
  }






  public function sendError($error, $errorMessages = [], $code = 200)
  {
    if (!empty($errorMessages)) {
      $response['data'] = $errorMessages;
    }
    $result = 'no';
    return response()->json($result, $code);
  }

  public function contact(Request $request)
  {
    $this->validate($request, [
      'fName' => 'required',
      'lName' => 'required',
      'email' => 'required',
      'message' => 'required',
    ]);
    $fName = $request->fName;
    $lName = $request->lName;
    $email = $request->email;
    $phone = $request->phone;


    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://eagportal.com/rest/6/1u4vedry50my5shh/crm.lead.add",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\r\n \"fields\":{\r\n \"NAME\" :\"$fName\",\r\n \"LAST_NAME\":\"$lName\",\r\n \"EMAIL\": [ { \"VALUE\": \"$email\"} ], \r\n \"PHONE\": [ { \"VALUE\": \"$phone\"} ],\r\n \"SOURCE_ID\" : 8\r\n }\r\n}",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Cookie: PHPSESSID=ckZJ2n9JKr29Vxk00ITzqsJEYwnEgurB; qmb=.; BITRIX_SM_SALE_UID=0"
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return back();
  }


  public function autoProcess(Request $request, $token)
  {
    try {
      $total_unprocessed_uploads = AdminUpload::where('is_processed', '1')->where('is_deleted', '1')->count();

      if ($total_unprocessed_uploads > 0 && $token == '5778195c3c38e8248e62f20a5d695681') {
        # code...
        $list = AdminUpload::where('is_processed', '1')->where('is_deleted', '1')->limit(1)->get();

        $process = $list[0];
        //$filename =asset($process->file_path);
        $filename = public_path();

        // Set Amazon s3 credentials
        $result = '';
        $client = S3Client::factory(
          array(
            'region' => "us-east-2",
            'version' => "latest",
            'credentials' => [
              'key'    => "AKIAIKDCJH6KDSTHWLVA",
              'secret' => "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"
            ]
          )
        );

        try {
          $result = $client->getObject(array(
            'Bucket' => 'files.dncblocker.com',
            'Key' =>  $process->file_path,
            'SaveAs' => $process->file_path
          ));
        } catch (S3Exception $e) {
          // Catch an S3 specific exception.
        }


        $fileX = file_get_contents($filename . "/" . $process->file_path);
        //$fileX=file_get_contents($result['body']);
        $templine = '';

        // Read in entire file
        $lines = $fileX;

        $dabc = substr($lines, 0, -3);
        try {
          if (DB::statement($dabc) === TRUE) {
            //echo "New records created successfully";
          } else {
            $Adminuploads = AdminUpload::find($process->id);
            $Adminuploads->is_processed = '3';
            $Adminuploads->save();
            return response()->json($token, 200);
          }
        } catch (\Exception $e) {
          $Adminuploads = AdminUpload::find($process->id);
          $Adminuploads->is_processed = '3';
          $Adminuploads->save();
          return response()->json($token, 200);
        }

        try {
          File::delete($process->file_path);
        } catch (\Exception $e) {
        }

        $Adminuploads = AdminUpload::find($process->id);
        $Adminuploads->is_processed = '2';
        $Adminuploads->save();
        return response()->json($process, 200);
      } else {
        return response()->json($token, 200);
      }
    } catch (\Exception $ex) {
      return response()->json($token, 200);
    }
  }


  public function insertnumber($number, $token, $type)
  {

    $row = DncList::where('phone_no', $number)->first();

    if (is_null($row)) {
      if ($token == '5778195c3c38e8248e62f20a5d695681') {
        $data = new DncList;
        $data->phone_no = $number;
        if ($type == 'federal') {
          $data->federal = 'yes';
          $data->litigator = 'no';
          $data->internal = 'no';
          $data->wireless = 'no';
        }

        if ($type == 'litigator') {
          $data->federal = 'no';
          $data->litigator = 'yes';
          $data->internal = 'no';
          $data->wireless = 'no';
        }

        if ($type == 'internal') {
          $data->federal = 'no';
          $data->litigator = 'no';
          $data->internal = 'yes';
          $data->wireless = 'no';
        }

        if ($type == 'wireless') {
          $data->federal = 'no';
          $data->litigator = 'no';
          $data->internal = 'no';
          $data->wireless = 'yes';
        }
        $data->uploaded_by = 'admin';
        $data->modified_by = 'admin';
        $data->save();
        return DncList::where('phone_no', $number)->first();
      }
    }
    return $row;
  }

  public function deletenumber($number, $token)
  {
    $data = DncList::where('phone_no', $number)->first();

    if ($data) {
      if ($token == '5778195c3c38e8248e62f20a5d695681') {
        $data->delete();
      }
      return $data;
    } else {

      return "NO DATA";
    }
  }

  // new method
  public function autoProcessScrub(Request $request, $token, $limit)
  {
    /*    try
{*/
    $total_unprocessed_uploads = TempScrubAdmin::where('dnc_list_id', NULL)->count();
    // $dnclistdatawithlimit=DB::select("select phone_no from dnc_lists limit $limit");
    $total_scrub_data = TempScrubAdmin::where('dnc_list_id', NULL)->limit($limit)->get();

    $numberdataarray[] = '';
    foreach ($total_scrub_data as $row) {
      $number_id[] = $row->id;
      $numberarray[] = $row->phone_no;
      $numberdataarray[] = $row->phone_no . '-' . $row->id;
    }

     // dd($total_scrub_data);
   
    if ($total_unprocessed_uploads > 0 && $token == '12345678900987654321123456789009') {
      $numberdata = json_encode($numberarray);
      $dat = substr($numberdata, 1, -1);
      $queryAllNumber='select dnc_lists.phone_no,dnc_lists.id from dnc_lists where phone_no IN ('.$dat.')';
     
      $data = DB::select($queryAllNumber);
      $tofile = "";
      $base_query = "UPDATE temp_scrub_admins SET dnc_list_id = CASE phone_no ";
      $tofile .= $base_query;
      $identicalarray[] = 'DNC';
     
   
      if ($data) {
       
        foreach ($data as $r) {
          $identicalarray[] = $r->phone_no;
          $arrayphone[] = $r->phone_no;
          //Dont add comma (,) at the last column value
          $tofile .= "when";
          $tofile .= "\"" . $r->phone_no . "\"";
          $tofile .= "then";
          $tofile .= "\"" . $r->id . "\"";
        }
        $tofile .= "ELSE dnc_list_id END WHERE phone_no IN(";
        $andata = json_encode($arrayphone);
        $dat = substr($andata, 1, -1);
        $tofile .=  "" . $dat . "";
        $tofile .= ")"; 
        DB::update($tofile);
      }
      $resultdiff = array_diff($numberarray, $identicalarray);
      $result = array_values($resultdiff);
      //dd(count($resultdiff));
      $jsonunidentical = json_encode($result);
      $jsonunidenticadata = substr($jsonunidentical, 1, -1);
     
      if($resultdiff){
        DB::update("update temp_scrub_admins set dnc_list_id=0 where phone_no IN ($jsonunidenticadata)");
      }
    }

    $Adminuploads = AdminScrubUpload::where([['identical_file_path', NULL],['is_dump','1']])->get();
    $adminid[] = "";
    $adminname[] = "";
    foreach ($Adminuploads as $adminuploads) {
      $adminid[] = $adminuploads->id;
      $adminname[] = $adminuploads->upload_name;
    }

    for ($i = 0; $i <= count($adminid) - 1; $i++) {
      $admin_id = $adminid[$i];
      $total_unprocessed_scrub_uploads = TempScrubAdmin::where('dnc_list_id', NULL)->where('admin_scrub_id', $admin_id)->count();

      AdminScrubUpload::where('id',$admin_id)->update(array('remaining_rows'=>$total_unprocessed_scrub_uploads));

      if ($total_unprocessed_scrub_uploads == 0) {
        $fname = 'Identical - outputfile-' . $adminname[$i] . '-' . rand(1000000, 100000000) . '.csv';
        $filedata = 'uploads/adminFiles/identicalscrubcsv/' . $fname;
        $fnameun = 'UnIdentical - outputfile-' . $adminname[$i] . '-' . rand(1000000, 100000000) . '.csv';
        $filedataun = 'uploads/adminFiles/Unidenticalscrubcsv/' . $fnameun;
        $tempdata = TempScrubAdmin::where('admin_scrub_id', $admin_id)->where('dnc_list_id', '!=', 0)->get();
        $identical_data_array[] = '';
        foreach ($tempdata as $value) {
          $identical_data_array[] = $value->phone_no;
        }
        $out = fopen($filedata, 'w+');
        fputcsv($out, $identical_data_array, "\n");
        $nonidenticaldata = TempScrubAdmin::where('admin_scrub_id', $admin_id)->where('dnc_list_id', 0)->get();
        $nonidentical_data_array[] = 'Clean';
        // Set Amazon s3 credentials
        $client = S3Client::factory(
          array(
            'region' => "us-east-2",
            'version' => "latest",
            'credentials' => [
              'key'    => "AKIAIKDCJH6KDSTHWLVA",
              'secret' => "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"
            ]
          )
        );
        try {
          $client->putObject(array(
            'Bucket' => 'files.dncblocker.com',
            'Key' =>  $filedata,
            'SourceFile' => $filedata,
            'ACL'        => 'public-read',
          ));
        } catch (S3Exception $e) {
          // Catch an S3 specific exception.
        }
        foreach ($nonidenticaldata as $non) {
          $nonidentical_data_array[] = $non->phone_no;
        }
        $outun = fopen($filedataun, 'w+');
        fputcsv($outun, $nonidentical_data_array, "\n");
        // Set Amazon s3 credentials
        $client = S3Client::factory(
          array(
            'region' => "us-east-2",
            'version' => "latest",
            'credentials' => [
              'key'    => "AKIAIKDCJH6KDSTHWLVA",
              'secret' => "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"
            ]
          )
        );
        try {
          $client->putObject(array(
            'Bucket' => 'files.dncblocker.com',
            'Key' =>  $filedataun,
            'SourceFile' => $filedataun,
            'ACL'        => 'public-read',
          ));
        } catch (S3Exception $e) {
          // Catch an S3 specific exception.
        }        
        $adminscrubuploads = AdminScrubUpload::find($admin_id);
        if ($adminscrubuploads) {
          $adminscrubuploads->identical_file_path = $filedata;
          $adminscrubuploads->unidentical_file_path = $filedataun;
          $adminscrubuploads->is_processed = '2';
          $adminscrubuploads->save();
        }
        try {
          File::delete($filedata);
          File::delete($filedataun);
        } catch (\Exception $e) {}
      }
    }
    /*}
catch(\Exception $e){
    dd('Main'.$e->getMessage());
  }*/
    return true;
  }

  // new method
  public function autoProcessScrubUser(Request $request, $token, $limit)
  {
      try {
        $total_unprocessed_uploads = TempScrubUser::where('user_dnc_list_id', NULL)->count();
        $total_scrub_data = TempScrubUser::where('user_dnc_list_id', NULL)->limit($limit)->get();
        $numberdataarray[] = '';
        foreach ($total_scrub_data as $row) {
            $number_id[] = $row->id;
            $numberarray[] = $row->phone_no;
            $numberdataarray[] = $row->phone_no . '-' . $row->id;
        }

        if ($total_unprocessed_uploads > 0 && $token == '12345678900987654321123456789009') {
            $numberdata = json_encode($numberarray);
            $dat = substr($numberdata, 1, -1);
            $queryAllNumber='select dnc_lists.phone_no,dnc_lists.id from dnc_lists where phone_no IN ('.$dat.')';     
            $data = DB::select($queryAllNumber);
            $tofile = "";
            $base_query = "UPDATE temp_scrub_users SET user_dnc_list_id = CASE phone_no ";
            $tofile .= $base_query;
            $identicalarray[] = 'DNC';        
            if ($data) {                       
                foreach ($data as $r) {
                    $identicalarray[] = $r->phone_no;
                    $arrayphone[] = $r->phone_no;          
                    $tofile .= "when";
                    $tofile .= "\"" . $r->phone_no . "\"";
                    $tofile .= "then";
                    $tofile .= "\"" . $r->id . "\"";
                }                
                $tofile .= "ELSE user_dnc_list_id END WHERE phone_no IN(";
                $andata = json_encode($arrayphone);
                $dat = substr($andata, 1, -1);
                $tofile .=  "" . $dat . "";
                $tofile .= ")"; 
                DB::update($tofile);
            }                
            $resultdiff = array_diff($numberarray, $identicalarray);
            $result = array_values($resultdiff);
            $jsonunidentical = json_encode($result);
            $jsonunidenticadata = substr($jsonunidentical, 1, -1);
            
            if($resultdiff){
                DB::update("update temp_scrub_users set user_dnc_list_id=0 where phone_no IN ($jsonunidenticadata)");
            }
        }
          
        $scrubuploads = ScrubUpload::where([['identical_file_path', NULL],['is_dump','1']])->get();    
        $userid[] = "";
        $username[] = "";
        foreach ($scrubuploads as $scrubupload) {
            $userid[] = $scrubupload->id;
            $username[] = $scrubupload->upload_name;
        }

        for ($i = 0; $i <= count($userid) - 1; $i++) {
            $user_id = $userid[$i];
            $total_unprocessed_scrub_uploads = TempScrubUser::where('user_dnc_list_id', NULL)->where('scrub_upload_id', $user_id)->count();
            ScrubUpload::where('id',$user_id)->update(array('remaining_rows'=>$total_unprocessed_scrub_uploads));

            if ($total_unprocessed_scrub_uploads == 0) {
                $fname = 'Identical - outputfile-' . $username[$i] . '-' . rand(1000000, 100000000) . '.csv';
                $filedata = 'uploads/userFiles/identicalscrubcsv/' . $fname;
                $fnameun = 'UnIdentical - outputfile-' . $username[$i] . '-' . rand(1000000, 100000000) . '.csv';
                $filedataun = 'uploads/userFiles/Unidenticalscrubcsv/' . $fnameun;
                $tempdata = TempScrubUser::where('scrub_upload_id', $user_id)->where('user_dnc_list_id', '!=', 0)->get();
                $identical_data_array[] = '';
                foreach ($tempdata as $value) {
                    $identical_data_array[] = $value->phone_no;
                }
                $out = fopen($filedata, 'w+');
                fputcsv($out, $identical_data_array, "\n");
                $nonidenticaldata = TempScrubUser::where('scrub_upload_id', $user_id)->where('user_dnc_list_id', 0)->get();
                $nonidentical_data_array[] = 'Clean';
                // Set Amazon s3 credentials
                $client = S3Client::factory(
                    array(
                        'region' => "us-east-2",
                        'version' => "latest",
                        'credentials' => [
                            'key'    => "AKIAIKDCJH6KDSTHWLVA",
                            'secret' => "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"
                        ]
                    )
                );
                try {
                    $client->putObject(array(
                        'Bucket' => 'files.dncblocker.com',
                        'Key' =>  $filedata,
                        'SourceFile' => $filedata,
                        'ACL' => 'public-read',
                    ));
                } catch (S3Exception $e) {
                    // Catch an S3 specific exception.                
                }
                foreach ($nonidenticaldata as $non) {
                    $nonidentical_data_array[] = $non->phone_no;
                }
                $outun = fopen($filedataun, 'w+');
                fputcsv($outun, $nonidentical_data_array, "\n");
                // Set Amazon s3 credentials
                $client = S3Client::factory(
                    array(
                        'region' => "us-east-2",
                        'version' => "latest",
                        'credentials' => [
                        'key'    => "AKIAIKDCJH6KDSTHWLVA",
                        'secret' => "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"
                        ]
                    )
                );
                try {
                    $client->putObject(array(
                        'Bucket' => 'files.dncblocker.com',
                        'Key' =>  $filedataun,
                        'SourceFile' => $filedataun,
                        'ACL'        => 'public-read',
                    ));
                } catch (S3Exception $e) {
                    // Catch an S3 specific exception.
                }
                $scrubuploads = ScrubUpload::find($user_id);
                if ($scrubuploads) {
                    $scrubuploads->identical_file_path = $filedata;
                    $scrubuploads->unidentical_file_path = $filedataun;
                    $scrubuploads->is_processed = '2';
                    $scrubuploads->save();
                }
                try {
                  File::delete($filedata);
                  File::delete($filedataun);
                } catch (\Exception $e) {}
            }
        }   
      } catch (\Exception $e) {
          dd('Main' . $e->getMessage());
      }
      return true;
  }


  public function manualfilterdata(Request $request, $token)
  {
    if ($request->list == null) {
      return Response::json(array('code' => '101', 'error' => 'List not found'));
    } else {
      if ($token == '12345678900987654321123456789009') {
        $array = explode(',', $request->list);
        $dat = $request->list;
        $datat = implode('","', $array);
        $finaldata = '"' . $datat . '"';
        $data = DB::select("select dnc_lists.phone_no,dnc_lists.id from dnc_lists where phone_no IN ($finaldata) GROUP BY dnc_lists.phone_no");
        $identicalarray[] = '';
        $UnIdentical[] = '';
        foreach ($data as $r) {
          $identicalarray[] = $r->phone_no;
        }
        $UnIdentical = array_diff($array, $identicalarray);

        return Response::json(array('code' => '200', 'dnc' => $identicalarray, 'non_dnc' => $UnIdentical));
      } else {
        return Response::json(array('code' => '101', 'error' => 'Token Mismatch'));
      }
    }
  }

  public function updateUploadFlag(Request $request, $userScrubId, $flag)
  {
    try{
      $scrubuploads = ScrubUpload::where('id', $userScrubId)->update(['is_upload'=>$flag]);
      return true;
    } catch (S3Exception $e) {
      return false;
    }
  }
}
