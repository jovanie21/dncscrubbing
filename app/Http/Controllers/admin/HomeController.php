<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\DNC;
use App\Models\User;
use App\Models\UserDncList;
use App\Models\AdminUpload;
use App\Models\DncList;
use App\Models\Contact;
use DB;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Zip;
use Log;
use App\Jobs\DncUploadJob;
use App\Models\DncImport;
use App\Models\Region;
use App\Models\RegionType;
use Exception;

class HomeController extends Controller
{
  public function uploaddncfiles(Request $request)
  {

    if ($request->hasFile('image')) {

      $file = $request->file('image');

      // Store the file temporarily in the storage directory
      $filePath = $file->store('temp', 'public');

      // Dispatch the job with the file path
      DncUploadJob::dispatch($filePath);
      // You can return a response indicating that the upload has been queued.
      return Redirect()->back()->with('success', 'File upload successfully');
    }

    return response()->json(['error' => 'No file was uploaded.'], 400);
  }
  public function index()
  {
    $total_user = User::whereHas('roles', function ($q) {
      $q->where('name', 'user');
    })->where('is_active', 1)->count();
    $total_contact_new = DB::select(DB::raw("SHOW TABLE STATUS WHERE Name='dnc_lists'"));

    //$total_user=0;
    $total_contact = $total_contact_new[0]->Rows;
    $total_processed_files = AdminUpload::where('is_processed', '2')->where('is_deleted', '1')->count();
    $total_unprocessed_files = AdminUpload::where('is_processed', '1')->where('is_deleted', '1')->count();
    $total_files = $total_processed_files + $total_unprocessed_files;
    $total_federal = 0;
    $total_litigator = 0;
    $total_internal = 0;
    $total_wireless = 0;
    return view('admin.home', compact('total_user', 'total_contact', 'total_federal', 'total_litigator', 'total_wireless', 'total_internal', 'total_processed_files', 'total_files'));
  }

  public function userscrublist()
  {
    $total_user = User::join('scrub_uploads', 'users.id', 'scrub_uploads.user_id')->where('is_deleted', '1')->get();
    return view('admin.userscrublist', compact('total_user'));
  }

  public function dnclist(Request $request)
  {
    $usersWithCompany = User::join('user_details', 'users.id', '=', 'user_details.user_id')
      ->select('users.id as user_id', 'user_details.company_name')
      ->where('users.name', '=', 'Company')
      ->where('users.is_active', '=', 1)
      ->get();
    if (request()->has('region')) {
      $region = Region::where('name', request()->region)->first();

      if ($region) {
        $dnc = DncList::where('region_id', $region->id)
          ->orderBy('id', 'DESC')
          ->paginate(10);
      }
    } else {
      if (isset($request->option)  && !empty($request->option)) {

        $dnc = DncList::orderBy('id', 'DESC')->where('uploaded_by', $request->option)
          ->paginate(10);
        // dd($dnc);
      } else {
        $dnc = DncList::orderBy('id', 'DESC')
          ->paginate(10);
      }
    }
    return view('admin.dnclist', [
      'dnc' => $dnc->appends(request()->except('page')),
      'usersWithCompany' => $usersWithCompany
    ]);
    return view('admin.dnclist', compact('dnc', 'usersWithCompany'));
  }


  public function getData(Request $request)
  {
    $query = DncList::select([
      DB::raw('id AS rownum'),
      'id',
      'phone_no',
      'federal',
      'litigator',
      'internal',
      'wireless',
      'uploaded_by',
      'modified_by',
      'created_at',
      'updated_at',
    ]);
    if ($request->filter_date) {
      $filter_date = $request->filter_date;
      $query->where($filter_date, 'yes');
    }

    if ($request->region) {
      $region = Region::where('name', request()->region)->first();
      $query->where('region_id', $region->id);
    }

    $total_contact_new = DB::select(DB::raw("SHOW TABLE STATUS WHERE Name='dnc_lists'"));

    $count1 = $total_contact_new[0]->Rows;

    return Datatables::of($query)->setTotalRecords($count1)
      ->editColumn('created_at', function ($datatables) {
        return date('d-M-Y h:i:s A', strtotime($datatables->created_at));
      })->editColumn('updated_at', function ($datatables) {
        return date('d-M-Y h:i:s A', strtotime($datatables->updated_at));
      })
      ->make(true);
  }

  public function uploadadmin()
  {
    $imports = DncImport::all();
    $regions = Region::all();
    return view('admin.uploadadmin', compact('imports', 'regions'));
  }

  public function uploadadminfile(Request $request)
  {
    Log::info($_FILES['file']['name']);

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if ($ext == "zip") {

      // dd(Zip::open($_FILES['file']['tmp_name']));
      # code...
      try {
        // $is_valid = Zip::check($_FILES['file']['tmp_name']);
        $zip = Zip::open($_FILES['file']['tmp_name']);
        // extract multiple files
        $data = $zip->extract('uploads/adminFiles/admincsv/', $zip->listFiles());


        foreach ($zip->listFiles() as $key) {

          $filecontent = 'uploads/adminFiles/admincsv/' . $key;
          $csv_data = file_get_contents($filecontent);
          $inputFile = 'uploads/adminFiles/admincsv/' . $key;
          $outputfilename = $request->file('file');

          $outputFile = basename($outputfilename->getClientOriginalName(), '.csv');;
          $splitSize = 200000;

          $in = fopen($inputFile, 'r');
          $files_array[] = '';

          $rowCount = 0;
          $fileCount = 1;
          while (!feof($in)) {
            if (($rowCount % $splitSize) == 0) {
              if ($rowCount > 0) {
                fclose($out);
              }

              $fname = $outputFile . rand(1000000, 100000000) . '-' . $fileCount++ . '.csv';
              $i = 'uploads/adminFiles/admincsv/' . $fname;
              $out = fopen($i, 'w+');

              $files_array[$fileCount] = $i;
            }
            $data = fgetcsv($in);
            if ($data)
              fputcsv($out, $data);
            $rowCount++;
          }

          fclose($out);
          chmod($i, 775);
          foreach ($files_array as $r) {
            if ($r != "") {
              $table_name = 'dnc_lists';
              $file_data = file_get_contents($r);
              $csv_array = explode("\n", $file_data);
              $cnt = 0;
              $tofile = "";
              $current_date_time = Carbon::now()->toDateTimeString();

              $db_columns = array('phone_no', 'federal', 'litigator', 'internal', 'wireless', 'uploaded_by', 'modified_by', 'created_at', 'updated_at', 'upload_path');
              $listtype = $request->contact_type;
              $yes = 'yes';
              $no = 'no';
              $column_names =  $db_columns[0];
              $num_columns = count($db_columns);
              //SET DEfualt col size to 1;
              $table_columns_sizes = [];

              for ($k = 0; $k < $num_columns; $k++) {
                $table_columns_sizes[$k] = 1;
              }
              $base_query = "INSERT INTO `dnc_lists`(`phone_no`, `federal`, `litigator`, `internal`, `wireless`, `uploaded_by`, `modified_by`,`upload_path`) VALUES ";

              $i = 0;
              $tofile .= $base_query;


              //CREATE TABLE
              $fSqlName = rand(1000000, 100000000) . '-' . (md5($table_name)) . '.sql';
              $filepath = 'uploads/adminFiles/db-backup-' . $fSqlName;

              for ($i = 0; $i < count($csv_array) - 1; $i++) {
                $oneVal = str_replace(',', '', $csv_array[$i]);
                $csv_row = explode(",", $oneVal);

                $counter = 0;
                foreach ($csv_row as $val) {
                  //Dont add comma (,) at the last column value
                  $cont = str_replace('"', '', $val);
                  if (($counter) + 1 == count($csv_row)) {
                    $tofile .= "(";
                    $tofile .= "\"" . $cont . "\",";
                    if ($listtype == 'federal') {
                      $tofile .= "\"" . $yes . "\",";
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $no . "\",";
                    }
                    if ($listtype == 'litigator') {
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $yes . "\",";
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $no . "\",";
                    }
                    if ($listtype == 'internal') {
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $yes . "\",";
                      $tofile .= "\"" . $no . "\",";
                    }
                    if ($listtype == 'wireless') {
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $no . "\",";
                      $tofile .= "\"" . $yes . "\",";
                    }
                    $tofile .= "\"" . Auth::user()->name . "\",";
                    $tofile .= "\"" . Auth::user()->name . "\",";
                    $tofile .= "\"" . $filepath . "\"";
                  } else {
                    $tofile .= "\"" . $cont . "\",";
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

              $handle = fopen($filepath, 'w+');
              fwrite($handle, $tofile);
              fclose($handle);

              // Set Amazon s3 credentials
              //  $client = S3Client::factory(
              //   array(

              //     'region' => "us-east-2",
              //     'version' => "latest",
              //     'credentials' => [
              //       'key'    => "AKIAIKDCJH6KDSTHWLVA",
              //       'secret' => "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"
              //     ]
              //   )
              // );

              //  try {
              //   $client->putObject(array(
              //     'Bucket'=>'files.dncblocker.com',
              //     'Key' =>  $filepath,
              //     'SourceFile' => $filepath,
              //     'ACL'        => 'public-read',
              //   ));

              // } catch (S3Exception $e) {
              //   // Catch an S3 specific exception.


              // }

              try {

                File::delete($filepath);
                File::delete($outputFile);
              } catch (\Exception $e) {
              }


              $data = new AdminUpload;
              $data->admin_id = Auth::user()->id;
              $data->is_processed = 1;
              $data->contact_type = $request->contact_type;
              $data->is_deleted = 1;
              $data->upload_name = $request->name;
              $data->file_path = $filepath;
              $data->save();
            }
          }
        }
      } catch (\Exception $e) {
        dd($e);
      }
    } else {
      $csv_data = file_get_contents($_FILES['file']['tmp_name']);
      $inputFile = $request->file;
      $outputfilename = $request->file('file');
      $outputFile = basename($outputfilename->getClientOriginalName(), '.csv');;
      $splitSize = 200000;

      $in = fopen($inputFile, 'r');

      $files_array[] = '';

      $rowCount = 0;
      $fileCount = 1;
      while (!feof($in)) {
        if (($rowCount % $splitSize) == 0) {
          if ($rowCount > 0) {
            fclose($out);
          }

          $fname = $outputFile . rand(1000000, 100000000) . '-' . $fileCount++ . '.csv';
          $i = 'uploads/adminFiles/admincsv/' . $fname;
          $out = fopen($i, 'w+');

          $files_array[$fileCount] = $i;
        }
        $data = fgetcsv($in);
        if ($data)
          fputcsv($out, $data);
        $rowCount++;
      }

      fclose($out);
      chmod($i, 775);
      foreach ($files_array as $r) {
        if ($r != "") {
          $table_name = 'dnc_lists';
          $file_data = file_get_contents($r);
          $csv_array = explode("\n", $file_data);
          $cnt = 0;
          $tofile = "";
          $current_date_time = Carbon::now()->toDateTimeString();

          $db_columns = array('phone_no', 'federal', 'litigator', 'internal', 'wireless', 'uploaded_by', 'modified_by', 'created_at', 'updated_at', 'upload_path');
          $listtype = $request->contact_type;
          $yes = 'yes';
          $no = 'no';
          $column_names =  $db_columns[0];
          $num_columns = count($db_columns);
          //SET DEfualt col size to 1;
          $table_columns_sizes = [];

          for ($k = 0; $k < $num_columns; $k++) {
            $table_columns_sizes[$k] = 1;
          }
          $base_query = "INSERT INTO `dnc_lists`(`phone_no`, `federal`, `litigator`, `internal`, `wireless`, `uploaded_by`, `modified_by`,`upload_path`) VALUES ";

          $i = 0;
          $tofile .= $base_query;


          //CREATE TABLE
          $fSqlName = rand(1000000, 100000000) . '-' . (md5($table_name)) . '.sql';
          $filepath = 'uploads/adminFiles/db-backup-' . $fSqlName;

          for ($i = 0; $i < count($csv_array) - 1; $i++) {
            $oneVal = str_replace(',', '', $csv_array[$i]);
            $csv_row = explode(",", $oneVal);

            $counter = 0;
            foreach ($csv_row as $val) {
              //Dont add comma (,) at the last column value
              $cont = str_replace('"', '', $val);
              if (($counter) + 1 == count($csv_row)) {
                $tofile .= "(";
                $tofile .= "\"" . $cont . "\",";
                if ($listtype == 'federal') {
                  $tofile .= "\"" . $yes . "\",";
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $no . "\",";
                }
                if ($listtype == 'litigator') {
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $yes . "\",";
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $no . "\",";
                }
                if ($listtype == 'internal') {
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $yes . "\",";
                  $tofile .= "\"" . $no . "\",";
                }
                if ($listtype == 'wireless') {
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $no . "\",";
                  $tofile .= "\"" . $yes . "\",";
                }
                $tofile .= "\"" . Auth::user()->name . "\",";
                $tofile .= "\"" . Auth::user()->name . "\",";
                $tofile .= "\"" . $filepath . "\"";
              } else {
                $tofile .= "\"" . $cont . "\",";
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

          $handle = fopen($filepath, 'w+');
          fwrite($handle, $tofile);
          fclose($handle);

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
              'Key' =>  $filepath,
              'SourceFile' => $filepath,
              'ACL'        => 'public-read',
            ));
          } catch (S3Exception $e) {
            // Catch an S3 specific exception.


          }

          try {

            File::delete($filepath);
            File::delete($outputFile);
          } catch (\Exception $e) {
          }


          $data = new AdminUpload;
          $data->admin_id = Auth::user()->id;
          $data->is_processed = 1;
          $data->contact_type = $request->contact_type;
          $data->is_deleted = 1;
          $data->upload_name = $request->name;
          $data->file_path = $filepath;
          $data->save();
        }
      }
    }



    session()->flash('success_msg', 'File has been Added successfully');
    return back();
  }

  public function processadminfile($id)
  {
    $process = AdminUpload::find($id);

    //$filename =asset($process->file_path);
    $filename = public_path();

    // Set Amazon s3 credentials
    // $result = '';
    // $client = S3Client::factory(
    //   array(

    //     'region' => "us-east-2",
    //     'version' => "latest",
    //     'credentials' => [
    //       'key'    => "AKIAIKDCJH6KDSTHWLVA",
    //       'secret' => "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"
    //     ]
    //   )
    // );

    // try {
    //   $result = $client->getObject(array(
    //     'Bucket' => 'files.dncblocker.com',
    //     'Key' =>  $process->file_path,
    //     'SaveAs' => $process->file_path
    //   ));
    // } catch (S3Exception $e) {
    //   // Catch an S3 specific exception.
    // }


    $fileX = file_get_contents($filename . "/" . $process->file_path);
    //$fileX=file_get_contents($result['body']);

    $templine = '';

    // Read in entire file
    $lines = $fileX;

    $dabc = substr($lines, 0, -3);
    try {
      if (DB::statement($dabc) === TRUE) {
        echo "New records created successfully";
      } else {
        echo "Error: <br>";
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
    }

    try {
      File::delete($process->file_path);
    } catch (\Exception $e) {
    }

    $Adminuploads = AdminUpload::find($id);
    $Adminuploads->is_processed = '2';
    $Adminuploads->save();
    session()->flash('success_msg', 'Contacts Imported Successful.');
    return back();
  }

  public function processabulkdminfile($id)
  {
    $process = AdminUpload::find($id);
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
        echo "New records created successfully";
      } else {
        echo "Error: <br>";
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
    }

    try {
      File::delete($process->file_path);
    } catch (\Exception $e) {
    }

    $Adminuploads = AdminUpload::find($id);
    $Adminuploads->is_processed = '2';
    $Adminuploads->save();

    return true;
  }

  public function deleteadminfile($id)
  {
    $process = AdminUpload::find($id);

    try {

      //DB::statement('DELET FROM ')
      DncList::where('upload_path', $process->file_path)->delete();
    } catch (\Exception $e) {
      dd($e);
    }



    File::delete($process->file_path);
    $process->delete();
    // $process->is_deleted='2';
    // $process->save();
    // $process->save();
    session()->flash('success_msg', 'File Deleted Successfully.');
    return back();
  }


  public function userlist()
  {
    $total_user = User::whereHas('roles', function ($q) {
      $q->where('name', 'user');
    })->get();
    return view('admin.userlist', compact('total_user'));
  }


  public function GetUserList(Request $request)
  {
    DB::statement(DB::raw('set @rownum=0'));
    $query = User::join('user_dnc_lists', 'users.id', 'user_dnc_lists.user_id')->select([
      DB::raw('@rownum  := @rownum  + 1 AS rownum'),
      DB::raw('users.id as id'),
      'user_dnc_lists.phone_no',
      'users.name',
      'user_dnc_lists.created_at',
      'user_dnc_lists.updated_at',
    ]);
    if ($request->filter_date) {
      $filter_date = $request->filter_date;
      $query->where('user_id', $filter_date);
    }
    return Datatables::of($query)
      ->editColumn('created_at', function ($datatables) {
        return date('d-M-Y h:i:s A', strtotime($datatables->created_at));
      })->editColumn('updated_at', function ($datatables) {
        return date('d-M-Y h:i:s A', strtotime($datatables->updated_at));
      })
      ->make(true);
  }



  public function viewcontact()
  {
    return view('admin.contactshow');
  }
  public function getcontactdata()
  {
    DB::statement(DB::raw('set @rownum=0'));
    $query = Contact::select([
      DB::raw('@rownum  := @rownum  + 1 AS rownum'),
      DB::raw('contacts.id as id'),
      'first_name',
      'last_name',
      'email',
      'phone_no',
      'message',
      'created_at',
      'updated_at',
    ]);

    return Datatables::of($query)
      ->editColumn('created_at', function ($datatables) {
        return date('d-M-Y h:i:s A', strtotime($datatables->created_at));
      })->editColumn('updated_at', function ($datatables) {
        return date('d-M-Y h:i:s A', strtotime($datatables->updated_at));
      })
      ->addColumn('action', function ($datatables) {
        return '<a href="' . url('admin/displaycontact', $datatables->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> View</a>';
      })
      ->make(true);
  }

  public function displaycontact($id)
  {
    $contact = Contact::find($id);
    return view('admin.contactdetail', compact('contact'));
  }

  public function findnumber(Request $request)
  {
    $mobile_number = $request->phone;
    $data = DncList::where('phone_no', $request->phone)->first();
    $dnc = DncList::orderBy('id', 'DESC')->limit(10)->get();
    return view('admin/dnclistwithsearch', compact('data', 'mobile_number', 'dnc'));
  }
  public function findpage(Request $request)
  {
    $page = $request->page;
    $records = $request->records;
    $dncdata = DncList::orderBy('id', 'DESC')->offset($request->page)->limit($request->records)->get();
    return view('admin/dnclistcustom', compact('dncdata', 'page', 'records'));
  }

  /**
   * Upload Bulk CSV
   */

  public function uploadCSV(Request $request)
  {
    // dd( $request);
    try {
      ini_set('upload_max_filesize', '10240M');
      ini_set('post_max_size', '10240M');
      if (empty($request->is_existing_file)) {
        $region = new Region();
        $region = $region->firstOrCreate(['name' => $request->name]);
        $region_id = $region->id;
        $dnc_import_name = $request->name;
      } else {
        if (!isset($request->region_id)) {
          session()->flash('danger_msg', 'Please  Select Region Name');
          return back();
        }
        $region_id = (int)$request->region_id;
        $region = Region::find($region_id);
        $dnc_import_name = $region->name;
      }


      $data = [
        'type' => $request->contact_type,
        'user' => auth()->user()->id,
        'rowData' => [
          'federal' => in_array('federal', $request->contact_type) ? 'yes' : 'no',
          'litigator' => in_array('litigator', $request->contact_type) ? 'yes' : 'no',
          'internal' => in_array('internal', $request->contact_type) ? 'yes' : 'no',
          'wireless' => in_array('wireless', $request->contact_type) ? 'yes' : 'no',
        ],
        'region_id' => $region_id,
        'client' => auth()->user()->name == 'company' ? true : false,
      ];
      $dncImport = new DncImport();
      $dncImport = $dncImport->create([
        'name' =>  $dnc_import_name,
        'path' => $request->file('file')->getClientOriginalName(),
        'user_id' => auth()->user()->id,
      ]);
      if(isset($request->contact_type) && !empty($request->contact_type)){
      $regionType = new RegionType();
        foreach($request->contact_type as $val){
          $regionType = $regionType->create([
            'region_id' =>  $region_id,
            'type' => $val,
          ]);
        }
    }
      $data['import_id'] = $dncImport->id;
      (new DNC($data))->import($request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
      session()->flash('success_msg', 'File has been Added successfully');
      return back();
    } catch (Exception $e) {
      dd($e->getMessage());
      Log::error($e->getMessage());
      session()->flash('danger_msg', 'Server Error');
      return back();
    }
  }
}
