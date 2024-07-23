<?php

 

namespace App\Http\Controllers\user;

 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DncList;
use App\Models\UserDncList;
use App\Models\TokenExpiery;
use App\Models\UserUpload;
use DB;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Models\DncImport;
use App\Models\Region;
use App\Imports\DNC;
use Log;
use Exception;

 


class HomeController extends Controller
{
    public function index()
    {
    $total_contact=UserDncList::where('user_id',Auth::user()->id)->count();
    return view('user.home',compact('total_contact'));
  }

 


  public function dncuserlist(){
    if (request()->has('region')) {
      $region = Region::where('name', request()->region)->first();

      if ($region) {
          $dnc = DncList::where('region_id', $region->id)
              ->orderBy('id', 'DESC')
              ->paginate(10);
      }
    } else {
        $dnc = DncList::where('uploaded_by',Auth::user()->id)->orderBy('id', 'DESC')
        ->paginate(10);
      }
   return view('user.dnclist',compact('dnc'));
 }

 public function uploaduser()
 {
   $imports = DncImport::Where('id',Auth::user()->id)->get();
   return view('admin.users.uploaduser', compact('imports'));
 }

 public function uploadCSV(Request $request)
  {
    try {
      $region = new Region();
      $region = $region->firstOrCreate(['name' => $request->name]);
      $data = [
        'type' => $request->contact_type,
        'user' => auth()->user()->id,
        'rowData' => [
          'federal' => in_array('federal', $request->contact_type) ? 'yes' : 'no',
          'litigator' => in_array('litigator', $request->contact_type) ? 'yes' : 'no',
          'internal' => in_array('internal', $request->contact_type) ? 'yes' : 'no',
          'wireless' => in_array('wireless', $request->contact_type) ? 'yes' : 'no',
        ],
        'region_id' => $region->id,
        'client' => auth()->user()->name == 'company' ? true : false,
      ];
      $dncImport = new DncImport();
      $dncImport = $dncImport->create([
        'name' => $request->name,
        'path' => $request->file('file')->getClientOriginalName(),
        'user_id' => auth()->user()->id,
      ]);
      $data['import_id'] = $dncImport->id;
      (new DNC($data))->import($request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
      session()->flash('success_msg', 'File has been Added successfully');
      return back();
    } catch (Exception $e) {
      Log::error($e->getMessage());
      session()->flash('danger_msg', 'Server Error');
      return back();
    }
  }
// public function userdnclist()
// {
//   if (request()->region) {
//     $region = Region::where('name', request()->region)->first();
//     $dnc = DncList::where('region_id', $region->id)->orderBy('id', 'DESC')->limit(10)->get();
//   } else
//     $dnc = DncList::orderBy('id', 'DESC')->limit(10)->get();
//   return view('admin.dnclist', compact('dnc'));
// }
 public function getData(Request $request)
 {

 

   DB::statement(DB::raw('set @rownum=0'));
   $query = UserDncList::select([
     DB::raw('@rownum  := @rownum  + 1 AS rownum'),
     DB::raw('user_dnc_lists.id as id'),
     'phone_no',
     'created_at',
     'updated_at',
   ])->where('user_id',Auth::user()->id);
   return Datatables::of($query)      
   ->editColumn('created_at', function ($datatables) {
    return date('d-M-Y h:i:s A', strtotime($datatables->created_at));
  })  ->editColumn('updated_at', function ($datatables) {
    return date('d-M-Y h:i:s A', strtotime($datatables->updated_at));
  })
  ->make(true);
}

public function findpage(Request $request)
{
  $page = $request->page;
  $records = $request->records;
  $dncdata = DncList::orderBy('id', 'DESC')->offset($request->page)->limit($request->records)->get();
  return view('user/dnclistcustom', compact('dncdata', 'page', 'records'));
} 

public function upload(){
  $uploaded=UserUpload::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->get();
  return view('user.upload',compact('uploaded'));
}

 

 


public function uploaduserfile(Request $request){

 

 

/*
        $data = new UserUpload;
        $data->user_id=Auth::user()->id;
        $data->is_processed=1;
        $data->is_deleted=1;
        $data->upload_name=$request->name;
        if ($request->hasFile('file')) {
          $image = $request->file('file');
          $filename = time().$image->getClientOriginalName();
          $destinationPath = public_path('uploads/userFiles');
          $image->move($destinationPath, $filename);
          $data->file_path= "uploads/userFiles/" . $filename;
        }
        $data->save();
        session()->flash('success_msg','File has been Added successfully');
        return back();*/

 

$csv_data = file_get_contents($_FILES['file']['tmp_name']);
$inputFile = $request->file;
$outputfilename=$request->file('file');
$outputFile = basename($outputfilename->getClientOriginalName(), '.csv'); ;
$splitSize = 200000;

 

$in = fopen($inputFile, 'r');

 

$files_array[]='';

 

$rowCount = 0;
$fileCount = 1;
while (!feof($in)) {
    if (($rowCount % $splitSize) == 0) {
        if ($rowCount > 0) {
            fclose($out);
        }


        $fname=$outputFile .rand(1000000,100000000).'-'. $fileCount++ . '.csv';
        $i='uploads/userFiles/usercsv/'.$fname;
        $out = fopen($i, 'w+');

        $files_array[$fileCount]=$i;
      }
    $data = fgetcsv($in);
    if ($data)
        fputcsv($out, $data);
    $rowCount++;
}
fclose($out);
foreach($files_array as $r){
if($r!=""){

  $table_name = 'user_dnc_lists';
  $file_data=file_get_contents($r);
 $csv_array = explode("\n", $file_data);
 $cnt = 0;
 $tofile = "";

 $db_columns=array('id','list_name','user_id','phone_no','created_at','updated_at','upload_path');
 $column_names =  $db_columns[0];
 $num_columns = count($db_columns);
 //SET DEfualt col size to 1;
 $table_columns_sizes = [];
 for ($k = 0; $k < $num_columns; $k++) {
     $table_columns_sizes[$k] = 1;
 }
 $base_query = "INSERT INTO `user_dnc_lists`(`list_name`, `user_id`, `phone_no`,`upload_path`) VALUES ";
 
 $i = 0;
     $tofile .= $base_query;

 $filepath='uploads/userFiles/db-backup-'.rand(1000000,100000000).'-'.(md5($table_name)).'.sql';
 for ($i = 0; $i < count($csv_array) - 1; $i++) {

     $oneVal = str_replace( ',', '', $csv_array[$i]);
     $csv_row = explode(",",$oneVal);
    // $csv_row = $csv_array[$i];
      $counter=0;

         //Dont add comma (,) at the last column value
         if ($csv_row) {
                 $tofile .="(";
             $tofile .="\"" . $request->name . "\",";
             $tofile .="\"" . Auth::user()->id . "\",";
             $tofile .="\"" . $csv_row . "\",";
             $tofile .="\"" . $filepath . "\"";
             
         } else {
             $tofile .="\"" . $csv_row . "\",";
         }
         //SET table col size
         if (strlen($csv_row) > $table_columns_sizes[$counter]) {
             $table_columns_sizes[$counter] = strlen($csv_row);
         }
         $counter++;
     $tofile .= "),\n";
     $cnt++;
 } 
 $tofile .= ";";
 //CREATE TABLE

 $handle = fopen($filepath,'w+');
  fwrite($handle,$tofile);
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
            'Bucket'=>'files.dncblocker.com',
            'Key' =>  $filepath,
            'SourceFile' => $filepath,
            'ACL'        => 'public-read',
          ));

        } catch (S3Exception $e) {
          // Catch an S3 specific exception.


        }


  try{
     
       File::delete($filepath) ;
       File::delete($outputFile) ;
     }catch(\Exception $e){

     }
       

 $data = new UserUpload;
        $data->user_id=Auth::user()->id;
        $data->is_processed=1;
        $data->is_deleted=1;
        $data->upload_name=$request->name;
          $data->file_path= $filepath;
        $data->save(); 
}
}
        session()->flash('success_msg','File has been Added successfully');
        return back();
      }
/*


public function uploaduserfile(Request $request){

        $data = new UserUpload;
        $data->user_id=Auth::user()->id;
        $data->is_processed=1;
        $data->is_deleted=1;
        $data->upload_name=$request->name;
        if ($request->hasFile('file')) {
          $image = $request->file('file');
          $filename = time().$image->getClientOriginalName();
          $destinationPath = public_path('uploads/userFiles');
          $image->move($destinationPath, $filename);
          $data->file_path= "uploads/userFiles/" . $filename;
        }
        $data->save();
        session()->flash('success_msg','File has been Added successfully');
        return back();
      }
*/


public function processfile($id){

$process=UserUpload::find($id);

//$filename =asset($process->file_path);
$filename =public_path();


// Set Amazon s3 credentials
    $result='';
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
         $result= $client->getObject(array(
            'Bucket'=>'files.dncblocker.com',
            'Key' =>  $process->file_path,
            'SaveAs' => $process->file_path
          ));

        } catch (S3Exception $e) {
          // Catch an S3 specific exception.
        }



$fileX=file_get_contents($filename."/". $process->file_path);

$templine = '';

// Read in entire file
$lines = $fileX;

$dabc=substr($lines,0,-3);
try{
if (DB::statement($dabc)=== TRUE) {
  echo "New records created successfully";
} 
else {
  echo "Error: <br>" ;
}
}
catch(\Exception $e){
  echo $e->getMessage();
}



     try{
        File::delete($process->file_path) ;
     }catch(\Exception $e){

     }


 $useruploads=UserUpload::find($id);
 $useruploads->is_processed='2';
 $useruploads->save();
 session()->flash('success_msg','Import Successful.');
 return back();
}

public function processbulkfile($id){

$process=UserUpload::find($id);

//$filename =asset($process->file_path);
$filename =public_path();

$fileX=file_get_contents($filename."/". $process->file_path);

$templine = '';

// Read in entire file
$lines = $fileX;

$dabc=substr($lines,0,-3);
try{
if (DB::statement($dabc)=== TRUE) {
  echo "New records created successfully";
} 
else {
  echo "Error: <br>" ;
}
}
catch(\Exception $e){
  echo $e->getMessage();
}

  try{
       File::delete($process->file_path) ;
     }catch(\Exception $e){

     }


 $useruploads=UserUpload::find($id);
 $useruploads->is_processed='2';
 $useruploads->save();
 return true;
}



  public function deletefile($id){
    $process=UserUpload::find($id);

    File::delete($process->file_path);
    $process->delete();
    // $process->is_deleted='2';
    // $process->save();
    // $process->save();
    session()->flash('success_msg','File Deleted Successfully.');
    return back();
  }

  public function tokendetail()
  {
    $details=TokenExpiery::where('user_id',Auth::user()->id)->get();
    return view('user/tokendetail',compact('details'));
  }


  public function check(Request $request){
    dd($request->all());
  }
}
