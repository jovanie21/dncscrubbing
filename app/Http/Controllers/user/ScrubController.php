<?php

namespace App\Http\Controllers\user;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ScrubUpload;
use App\Models\UserDetail;
use App\Models\DncImport;
use Auth;
use DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\UserDncList;
use App\Models\DncExport;
use App\Models\Region;
use App\Http\Traits\DropBox;
use Illuminate\Support\Facades\Storage;
use App\Imports\DNCSeperator;


class ScrubController extends Controller
{
  use DropBox;

  public function scrubupload()
  {
    $exports = DncExport::Where('id',Auth::user()->id)->get();
		$regions = Region::all();
    return view('user.scrub.scrubupload',compact('exports', 'regions'));
  }

  public function userstorescrubfile(Request $request)
  {
    /* :not in use 		
      $csv_data = file_get_contents($_FILES['file']['tmp_name']);
      $inputFile = $request->file;
      $outputfilename=$request->file('file');
      $outputFile = basename($outputfilename->getClientOriginalName(), '.csv'); ;
      $splitSize = 200000;

      $in = fopen($inputFile, 'r');
    */

    $files_array[]='';

    $rowCount = 0;
    $fileCount = 1;
    $table_name = 'dnc_lists';
    $file_data=file_get_contents($request->file);

    $csv_array = explode("\n", $file_data);
    $cnt = 0;
    $tofile = "";
    $current_date_time = Carbon::now()->toDateTimeString();

    $db_columns=array(`user_id`, `scrub_upload_id`, `phone_no`, `created_at`,`updated_at`);
    $column_names =  $db_columns[0];
    $num_columns = count($db_columns);
    //SET DEfualt col size to 1;
    $table_columns_sizes = [];

    for ($k = 0; $k < $num_columns; $k++) {
      $table_columns_sizes[$k] = 1;
    }
    $base_query = "INSERT INTO `temp_scrub_users`(`user_id`, `scrub_upload_id`, `phone_no`, `created_at`, `updated_at`) VALUES ";

    $i = 0;
    $tofile .= $base_query;
      //CREATE TABLE
    $fSqlName=rand(1000000,100000000).'-'.(md5($table_name)).'.sql';
    $filepath='uploads/userFiles/scrub-'.$fSqlName;    
    $data = new ScrubUpload;
    $data->user_id=Auth::user()->id;
    $data->is_processed=1;
    $data->is_deleted=1;
    $data->upload_name=$request->name;
    $data->file_path=$filepath; 
    $data->total_rows = count($csv_array);
    $data->save();  

    $user_scrub_id=$data->id;


    for ($i = 0; $i < count($csv_array) - 1; $i++) 
    {
      $oneVal = str_replace( ',', '', $csv_array[$i] );
      $csv_row = explode(",",$oneVal);
      $counter = 0;
      foreach ($csv_row as $val) {
         //Dont add comma (,) at the last column value
        $cont = str_replace('"', '', $val);
        $numberphone=str_replace("\r","",$val); 
        if (($counter) + 1 == count($csv_row)) {
          $tofile .="(";
          $tofile .="\"" .Auth::user()->id. "\",";
          $tofile .="\"" .$user_scrub_id. "\",";
          $tofile .="\"" .$numberphone. "\",";
          $tofile .="\"" .$current_date_time. "\",";
          $tofile .="\"" .$current_date_time. "\"";

        } else {
          $tofile .="\"" . $cont . "\",";
        }
         //SET table col size
        if (strlen($cont) > $table_columns_sizes[$counter]) {
          $table_columns_sizes[$counter] = strlen($cont);
        }
        $counter++;
      }
      $tofile .= "),\n";
      $cnt++;

    } 
    $tofile .= ";";
    /* :not required
      $handle = fopen($filepath,'w+');
      fwrite($handle,$tofile);
      fclose($handle);
    */

    
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
        'Bucket'=>'files.dncblocker.com',
        'Key' =>  $filepath,
        /*'SourceFile' => $filepath,*/
        'Body'	=> $tofile,
        'ACL'        => 'public-read',
      ));
    } catch (S3Exception $e) {
          // Catch an S3 specific exception.
    }


    /*
      $fileX=file_get_contents($filepath);
      //$fileX=file_get_contents($result['body']);
      $templine = '';
      // Read in entire file
      $lines = $fileX;
      $dabc=substr($lines,0,-3);
      try{
        if (DB::statement($dabc)=== TRUE) 
        {
          echo "New records created successfully";
        } 
        else 
        {
          echo "Error: <br>" ;
        }
      }
      catch(\Exception $e){
        echo $e->getMessage();exit;
      }
      try{

        File::delete($filepath) ;
        File::delete($outputFile) ;
      }catch(\Exception $e){
      }
    */
    session()->flash('success_msg','File has been Added successfully');
    return back();
  }

  // public function userstorescrubfilewithmicroservice(Request $request)
  // {
  //   try {
  //     $current_date_time = Carbon::now()->toDateTimeString();

  //     $table_name = 'dnc_lists';
  //     $fSqlName=rand(1000000,100000000).'-'.(md5($table_name)).'.sql';
  //     $filePath='uploads/userFiles/scrub-'.$fSqlName;
  //     $file_data = file_get_contents($request->file);
  //     $csv_array = explode("\n", $file_data);

  //     $data = new ScrubUpload;
  //     $data->user_id=Auth::user()->id;
  //     $data->is_processed=1;
  //     $data->is_deleted=1;
  //     $data->upload_name=$request->name;
  //     $data->file_path=$filePath; 
  //     $data->total_rows = count($csv_array);
  //     $data->save();  

  //     $user_scrub_id=$data->id;

  //     $apiEndpoint = env('MICRO_SERVICE_URL');
  //     $client = new Client();
  //     $res2 = $client->request('POST', $apiEndpoint . "userScrubFile", [
  //             'multipart' => [
  //               [
  //                 'name'     => 'csv',
  //                 'contents' => file_get_contents($request->file),
  //                 'filename' => $request->file->getClientOriginalName()
  //               ],
  //               [
  //                 'name'     => 'userId',
  //                 'contents' => Auth::user()->id
  //               ],
  //               [
  //                 'name'     => 'userScrubId',
  //                 'contents' => $user_scrub_id
  //               ],
  //               [
  //                 'name'     => 'date',
  //                 'contents' => $current_date_time
  //               ],
  //               [
  //                 'name'     => 'filePath',
  //                 'contents' => $filePath
  //               ],
  //               [
  //                 'name'     => 'token',
  //                 'contents' => env('AUTH_TOKEN')
  //               ],
  //             ]
  //         ]);
  //     if($res2->getStatusCode() == 200){
  //       session()->flash('success_msg','File has been Added successfully');
  //       return back();
  //     }
  //   } catch (S3Exception $e) {
  //     session()->flash('success_msg','Something went wrong!');
  //     return back();
  //   }
  // }

  public function seperateList(Request $request)
	{
		try {
			$path = time() . '_' . $request->file('file')->getClientOriginalName();
			$contents = file_get_contents($request->file('file'));
			// $this->upload($path, $contents);
			$regions = Region::whereIn('id', $request->region)->get()->toArray();
			$regions = array_column($regions, 'name', 'id');
			$data = [
				'user' => auth()->user()->id,
				'regions' => $regions,
				'type' => $request->type,
				'option' => $request->option,
			];
			if ($request->option == 'combined') {
				$data['filenames'] = [
					'active' => time() . '_active_' . '.csv',
					'inactive' => time() . '_inactive_' . '.csv'
				];
				$activeFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['active']);
				$activeFile = fopen($activeFilePath, 'w');
				fputcsv($activeFile, ['phone', 'federal', 'litigator', 'internal', 'wireless']);
				fclose($activeFile);
				$inactiveFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['inactive']);
				$inactiveFile = fopen($inactiveFilePath, 'w');
				fputcsv($inactiveFile, ['phone']);
				fclose($inactiveFile);
			} else {
				foreach ($regions as $id => $region) {
					$region = str_replace(' ', '_', $region);
					$data['filenames'][$id] = [
						'active' => time() . '_' . $region . '_active_' . '.csv',
						'inactive' => time() . '_' . $region . '_inactive_' . '.csv'
					];

					#active
					$activeFilePath = Storage::disk('dnc-seperated')->path($data['filenames'][$id]['active']);
					$activeFile = fopen($activeFilePath, 'w');
					fputcsv($activeFile, ['phone', 'federal', 'litigator', 'internal', 'wireless']);
					fclose($activeFile);

					#inactive
					$inactiveFilePath = Storage::disk('dnc-seperated')->path($data['filenames'][$id]['inactive']);
					$inactiveFile = fopen($inactiveFilePath, 'w');
					fputcsv($inactiveFile, ['phone']);
					fclose($inactiveFile);
				}
			}
			$dncExport = new DncExport();
			$dncExport = $dncExport->create([
				'name' => $request->name,
				'paths' => $data['filenames'],
				'user_id' => auth()->user()->id,
			]);
			$data['export_id'] = $dncExport->id;
			(new DNCSeperator($data))->queue($request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
			session()->flash('success_msg', 'File has been Added successfully');
			return back();
		} catch (Exception $e) {
			Log::error($e);
			session()->flash('danger_msg', 'Server Error');
			return back();
		}
	}

  /* scrub-dump */
  public function scrubdump()
  {		
    $data = ScrubUpload::where('is_dump','0')->first();
    if(!$data)
    {
      echo "uptodate";
      return;
    }		
    
    // $filepath = "testfile.sql";		
    $s3url = "https://s3.us-east-2.amazonaws.com/files.dncblocker.com/";
    $filepath = $s3url.$data->file_path;
    $fileX = file_get_contents($filepath);		
    $dabc = substr($fileX, 0, -3);

    try {
      if (DB::statement($dabc) === TRUE) {
        $data->is_dump = 1;	// success
        $data->save();
        echo "Success: <br>";
      } else {
        echo "Error: <br>";
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
      exit;
    }					
  }
  /* scrub-dump */
  
  /* send-mail */
  public function sendmail()
  {
    $dataAr = ScrubUpload::where('identical_file_path','!=',NULL)->where('is_mail','0')->get();
    foreach($dataAr as $data){
      $data->is_mail = 1;
      $data->save();			
      $user_mail = User::find($data->id);
      if($user_mail){ 
        $user_mail=$user_mail->email;      
        try{
          \Mail::send('mail.admin_scrub_upload', json_decode(json_encode($data),true), function ($emailMessage) use($user_mail){
            $emailMessage->subject('DNCScrubbing Report');
            $emailMessage->to($user_mail);
            $emailMessage->cc("eagteam@eag.llc");
          });
        }
        catch(\Exception $e){
          echo $e->getMessage();
        }
      }                   
    }
    echo count($dataAr)." Updated";
  }
  /* send-mail */
  
  /* pdf */
  public function pdf($id)
  {

    $total_data = ScrubUpload::find($id);

    $row = ScrubUpload::join('users', 'scrub_uploads.id', 'users.id')->where('users.id', $total_data->user_id)->first();
    //dd($row->name);	for testing purpose only
    $companyname = UserDetail::join('users', 'user_details.user_id', 'users.id')->where('user_details.user_id', $total_data->user_id)->first();
    //dd($companyname->name);

    $counttotal = ScrubUpload::join('temp_scrub_users', 'scrub_uploads.id', 'temp_scrub_users.scrub_upload_id')->where('scrub_upload_id', $id)->count();
    $clean = ScrubUpload::join('temp_scrub_users', 'scrub_uploads.id', 'temp_scrub_users.scrub_upload_id')->where('scrub_upload_id', $id)->where('user_dnc_list_id', '0')->count();
    $dnc = ScrubUpload::join('temp_scrub_users', 'scrub_uploads.id', 'temp_scrub_users.scrub_upload_id')->where('scrub_upload_id', $id)->where('user_dnc_list_id', '!=', '0')->count();
    // $ipaddress = getenv('HTTP_CLIENT_IP');
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {

      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {

      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      //$ipaddress=192.24;
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
    $pdf = \App::make('dompdf.wrapper');
    $temp_body = "<!DOCTYPE html>
    <html lang='en' >
    <body style='border:1px solid #000; padding:9px;'>
    <div id='invoice-POS' >
    <center id='top'>
    <div class='info'>
    <center><img src='https://dncscrubbing.com/public/webtheme/img/logo.png' width='180% !important'></center>
    <p style='font-size:28px; line-height: 0px !important; color:blue;' >$total_data->upload_name</p>
    </div>
    </center>
    <div id='mid'>
    <hr>
    <div class='info'>
    <div id='bot'>
    <div id='table'>
    <table>
    <tr>
    <td>
    <p>Please find the below receipt of the <span style='color:green;'><strong><u><i>";
      $temp_body .= $total_data->upload_name . "</i></u></strong></span> </p>
    </td>
    </tr>

    <tr>
    <td><h4><span style='color:red;'>User Name:-</span>";
      $temp_body .= $companyname->name . "</h4></td>
    
    <td><h4><span style='color:red;'>Email Id:-</span>";
      $temp_body .= $companyname->email . "</h4></td>
    </tr>

    <tr>
    <td><h4><span style='color:red;'>Company Name:-</span>";
      $temp_body .= $companyname->company_name . "</h4></td>
    <td><h4><span style='color:red;'>Total Contacts:-</span>";
      $temp_body .= $counttotal . "</h4></td>
    </tr>
    <tr>
    <td><h4><span style='color:red;'>Dnc Contacts:-</span>";
      $temp_body .= $dnc . "</h4></td>
    <td><h4><span style='color:red;'>Clean Contacts:-</span>";
      $temp_body .= $clean . "</h4></td>
    </tr>
    <tr>
    <td><h4><span style='color:red;'>IP Address:-</span>";
      $temp_body .= $ipaddress . "</h4></td>
    <td><h4><span style='color:red;'>Time Stamp:-</span>";
      $temp_body .= date('d-F-Y', strtotime($total_data->created_at)) . "</h4></td>
    </tr>
    </table>
    </div><!--End Table-->
    <div id='legalcopy'>
    <br><br><br><br><br><br><br><br>
    <h6 style='text-align: center'><strong>Thank you!</strong>
    <hr>
    <h2 style='text-align: center'>Have a Nice Day</h2>
    </h6>
    <hr>
    </div>
    <center><img src='https://dncscrubbing.com/public/webtheme/img/logo.png'width='100% !important'></center>
    </div><!--End Invoice-->
    </div><!--End InvoiceBot-->
    </body>
    </html>
    ";
    $pdf->loadHTML($temp_body)->setPaper('A4');
    return $pdf->stream();
  } 
  /* pdf */
	public function downloadOriginal($id)
	{
		try {
			$export = DncExport::find($id);
			$path = $export->original_path;
			$link = $this->download($path);
			if ($link)
				return redirect($link);
			session()->flash('danger_msg', 'File Not Found');
			return redirect()->back();
		} catch (Exception $e) {
			Log::error($e->getMessage());
			session()->flash('danger_msg', 'File Not Found');
			return back();
		}
	}
}
