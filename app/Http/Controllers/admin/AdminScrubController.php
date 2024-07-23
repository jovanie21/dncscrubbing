<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Jobs\FileUploadJob;
use App\Models\AdminScrubUpload;
use App\Models\DncList;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use App\Jobs\UploadFileToS3;
use Auth;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Carbon\Carbon;
//use
use PDF;
use App;
use Log;
use App\Exports\DNC;
use App\Http\Traits\DropBox;
use App\Imports\DNCSeperator;
use App\Models\DncExport;
use App\Models\Region;
use App\Models\RegionType;
use Exception;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AdminScrubController extends Controller
{
	use DropBox;

	public function zipdownload($active, $inactive, $invalid = null)
	{

		$zip = new ZipArchive;
		$zipFileName = 'DncNonDnc_' . now()->format('Ymd_His') . '.zip'; // Unique zip file name
		if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
			$csvFilePaths = [
				$active,     // Path to the first CSV file

				$inactive,   // Path to the second CSV file

				$invalid,    // Path to the third CSV file
			];

			// Loop through the array and add each CSV file to the zip
			foreach ($csvFilePaths as $index => $csvFilePath) {
				$activefile = strstr($csvFilePath, 'active.csv');
				if ($activefile == 'active.csv') {
					info($zip->addFile('dnc-seperated/' . $csvFilePath, 'active.csv'));
					$zip->addFile('dnc-seperated/' . $csvFilePath, 'active.csv');
				}

				$inActivefile = strstr($csvFilePath, 'inactive.csv');
				if ($inActivefile == 'inactive.csv') {
					$zip->addFile('dnc-seperated/' . $csvFilePath, 'inactive.csv');
				}

				$invalid = strstr($csvFilePath, 'invalid.csv');
				if ($invalid == 'invalid.csv') {
					$zip->addFile('dnc-seperated/' . $csvFilePath, 'invalid.csv');
				}
			}
			$zip->close();
		}
		$headers = [
			'Content-Type' => 'application/zip',
		];
		// Return the zip file as a response
		return response()->download($zipFileName, $zipFileName, $headers);
	}
	public function scrubupload()
	{
		$exports = DncExport::all();
		//dd($exports);
		$regions = Region::all();
		return view('admin.scrub.scrubupload', compact('exports', 'regions'));
	}

	public function dnclistfillter(Request $request)
	{

		$check = $request->checkedValues;
		$checkedArray = array_map(function ($item) {
			return $item['name'];
		}, $check);

		// dd($);
		$fillterList = RegionType::whereIn('type', $checkedArray)->with('region')->get();
		return response()->json($fillterList);
	}
	/* scrub-dump */
	public function scrubdump()
	{
		$data = AdminScrubUpload::where('is_dump', '0')->first();
		if (!$data) {
			echo "uptodate";
			return;
		}

		// $filepath = "testfile.sql";		
		$s3url = "https://s3.us-east-2.amazonaws.com/files.dncblocker.com/";
		$filepath = $s3url . $data->file_path;
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
		$dataAr = AdminScrubUpload::where('identical_file_path', '!=', NULL)->where('is_mail', '0')->get();
		foreach ($dataAr as $data) {
			$data->is_mail = 1;
			$data->save();
			try {
				\Mail::send('mail.admin_scrub_upload', json_decode(json_encode($data), true), function ($emailMessage) {
					$emailMessage->subject('DNCScrubbing Report');
					$emailMessage->to("eagteam@eag.llc");
				});
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
		}
		echo count($dataAr) . " Updated";
	}
	/* send-mail */

	// scheduler for 30days of deletion
	public function deleteOldData()
	{

		//	$data = AdminScrubUpload::where('created_at', '>=', Carbon::now()->subDays(3)->toDateTimeString())->get();

		$date = \Carbon\Carbon::now()->subDays(30);
		$olddata = AdminScrubUpload::where('created_at', '<=', $date)->get();
		//dd($olddata);
		foreach ($olddata as $data) {
			//dd($data);
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
			$result = $client->deleteObject(array(
				'Bucket' => "files.dncblocker.com",
				'Key'    => $data->file_path,

			));
			$data->delete();
		}
		// $olddata->deletion_status = 1;
		// $olddata->save();


	}

	public function genratepdf($id)
	{
		session_start();
		$_SESSION["id"] = $id;
		return redirect('TCPDF/examples/example_061.php');
		//return redirect()->route('TCPDF/examples/example_061.php');
		//header("location: TCPDF/examples/example_061.php");
	}

	public function pdf($id)
	{
		$total_data = AdminScrubUpload::find($id);
		$allDataCount = AdminScrubUpload::where('id', $id)->first();
		// print_r($allDataCount->dnc_count);die();
		$row = AdminScrubUpload::join('users', 'admin_scrub_uploads.id', 'users.id')->where('users.id', $total_data->admin_id)->first();
		//dd($row->name);	for testing purpose only
		$companyname = UserDetail::join('users', 'user_details.user_id', 'users.id')->where('user_details.user_id', $total_data->admin_id)->first();
		//dd($companyname->name);

		$counttotal = AdminScrubUpload::join('temp_scrub_admins', 'admin_scrub_uploads.id', 'temp_scrub_admins.admin_scrub_id')->where('admin_scrub_id', $id)->count();
		$clean = AdminScrubUpload::join('temp_scrub_admins', 'admin_scrub_uploads.id', 'temp_scrub_admins.admin_scrub_id')->where('admin_scrub_id', $id)->where('dnc_list_id', '0')->count();
		$dnc = AdminScrubUpload::join('temp_scrub_admins', 'admin_scrub_uploads.id', 'temp_scrub_admins.admin_scrub_id')->where('admin_scrub_id', $id)->where('dnc_list_id', '!=', '0')->count();
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
		$pdf = App::make('dompdf.wrapper');
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
		$temp_body .= $allDataCount->dnc_count + $allDataCount->non_dnc_count . "</h4></td>
		</tr>
		<tr>
		<td><h4><span style='color:red;'>Dnc Contacts:-</span>";
		$temp_body .= $allDataCount->dnc_count . "</h4></td>
		<td><h4><span style='color:red;'>Clean Contacts:-</span>";
		$temp_body .= $allDataCount->non_dnc_count . "</h4></td>
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

	/**
	 * Seperate DNC/Non DNC List
	 */
	/*
	public function seperateList_old(Request $request)
	{			 
		$user = auth()->user();
		$type = $user->roles->contains('name', 'admin') ? 'global' : '';
		
		try {
			$path = time() . '_' . $request->file('file')->getClientOriginalName();
			$contents = file_get_contents($request->file('file'));
			// $this->upload($path, $contents);
			$regions = Region::whereIn('id', $request->region)->get()->toArray();
			$regions = array_column($regions, 'name', 'id');
			$data = [
				'user' => auth()->user()->id,
				'regions' => $regions,
				'type' => $type,
				'option' => $request->option,
				'rowData' => [
					'federal' => in_array('federal', $request->type1) ? 'yes' : 'no',
					'litigator' => in_array('litigator', $request->type1) ? 'yes' : 'no',
					'internal' => in_array('internal', $request->type1) ? 'yes' : 'no',
					'wireless' => in_array('wireless', $request->type1) ? 'yes' : 'no',
				  ],
			];
			if ($request->option == 'combined') {
				$data['filenames'] = [
					'active' => time() . '_active' . '.csv',
					'inactive' => time() . '_inactive' . '.csv'
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
						'active' => time() . '_' . $region . '_active' . '.csv',
						'inactive' => time() . '_' . $region . '_inactive' . '.csv'
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
	*/

	public function seperateList(Request $request)
	{
		ini_set('upload_max_filesize', '10240M');
		ini_set('post_max_size', '10240M');
		$user = auth()->user();
		$type = $user->roles->contains('name', 'admin') ? 'global' : '';

		try {
			$path = time() . '_' . $request->file('file')->getClientOriginalName();
			$contents = file_get_contents($request->file('file'));
			// $this->upload($path, $contents);
			if (isset($request->region)) {
				$regions = Region::whereIn('id', $request->region)->get()->toArray();
				$is_region = true;
			} else {

				// $regions = Region::whereIn('id', function ($query) use ($request) {
				// 	$query->select('id')
				// 		->from('regions_types')
				// 		->whereIn('type', array_keys($request->type1));
				// })->get()->toArray();


				// code written by rakesh
				$regions = Region::whereIn('id', RegionType::whereIn('type', array_keys($request->type1))->pluck('region_id')->toArray())->get()->toArray();
				$is_region = false;
				//old 
				// $regions = Region::select('name', 'id')->get()->toArray();
				// $is_region = false;
			}

			//	dd($regions);
			// $regions = Region::whereIn('id', $request->region)->get()->toArray();
			$regions = array_column($regions, 'name', 'id');
			//dd($regions);
			$data = [
				'user' => auth()->user()->id,
				'regions' => $regions,
				'type' => $type,
				'option' => $request->option,
				'rowData' => isset($request->type1) ? $request->type1 : 0,
				'is_region' => $is_region,
			];

			if ($request->option == 'combined') {
				try {
					/*
					$data['filenames'] = [
						'combined' => time() . '_DNC_NON_DNC' . '.xlsx',
					];

					// #active
					// $activeFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['active']);
					$filePath = Storage::disk('dnc-seperated')->path($data['filenames']['combined']);

					$spreadsheet = new Spreadsheet();

					// Create a new Spreadsheet

					// Create an array of sheet names
					$data['sheetNames'] = [
						'dnc' => [
							'headers' => ['phone', 'federal', 'litigator', 'internal', 'wireless']
						],
						'non_dnc' => [
							'headers' => ['phone']
						]
					];


					// Iterate over sheet names and create sheets
					foreach ($data['sheetNames'] as $sheetName => $sheet) {
						// Create a new sheet
						$worksheet = $spreadsheet->createSheet();
						$worksheet->setTitle($sheetName);
					}

					$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
					$writer->save($filePath);

					// load file
					$spreadsheet = IOFactory::load($filePath);
					// remove sheet
					$sheetIndex = $spreadsheet->getIndex(
						$spreadsheet->getSheetByName('Worksheet')
					);
					$spreadsheet->removeSheetByIndex($sheetIndex);

					$sheetNames = $spreadsheet->getSheetNames();

					foreach ($sheetNames as $sheetName) {
						// write headers
						$worksheet = $spreadsheet->getSheetByName($sheetName);
						$worksheet->fromArray($data['sheetNames'][$sheetName]['headers']);
					}

					$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
					$writer->save($filePath);


					$data['filePath'] = $filePath;
				
				*/
					$data['filenames'] = [
						'dnc' => time() .  '_dnc' . '.csv',
						'non_dnc' => time() .  '_non_dnc' . '.csv',
						//'invalid' => time() .  '_invalid' . '.csv'
					];

					#active
					$activeFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['dnc']);
					$activeFile = fopen($activeFilePath, 'w');
					fputcsv($activeFile, ['phone', 'federal', 'litigator', 'internal', 'wireless']);
					fclose($activeFile);

					#inactive
					$inactiveFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['non_dnc']);
					$inactiveFile = fopen($inactiveFilePath, 'w');
					fputcsv($inactiveFile, ['phone']);
					fclose($inactiveFile);
					//dd($data);
					// Invalid dnc file
					// $invalidFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['invalid']);
					// $invalidFile = fopen($invalidFilePath, 'w');
					// fclose($invalidFile);
				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
			} else {


				// foreach ($regions as $id => $region) {
				// 	$region = str_replace(' ', '_', $region);
				// 	$data['filenames'][$id] = [
				// 		'dnc' => time() . '_' . $region . '_dnc' . '.csv',
				// 		'non_dnc' => time() . '_' . $region . '_non_dnc' . '.csv',
				// 		'invalid' => time() . '_' . $region . '_invalid' . '.csv'
				// 	];

				// 	#active
				// 	$activeFilePath = Storage::disk('dnc-seperated')->path($data['filenames'][$id]['dnc']);
				// 	$activeFile = fopen($activeFilePath, 'w');
				// 	fputcsv($activeFile, ['phone', 'federal', 'litigator', 'internal', 'wireless']);
				// 	fclose($activeFile);

				// 	#inactive
				// 	$inactiveFilePath = Storage::disk('dnc-seperated')->path($data['filenames'][$id]['non_dnc']);
				// 	$inactiveFile = fopen($inactiveFilePath, 'w');
				// 	fputcsv($inactiveFile, ['phone']);
				// 	fclose($inactiveFile);
				// 	//dd($data);
				// 	// Invalid dnc file
				// 	$invalidFilePath = Storage::disk('dnc-seperated')->path($data['filenames'][$id]['invalid']);
				// 	$invalidFile = fopen($invalidFilePath, 'w');
				// 	fclose($invalidFile);
				// }



				$data['filenames'] = [
					'dnc' => time() .  '_dnc' . '.csv',
					'non_dnc' => time() .  '_non_dnc' . '.csv',
					'invalid' => time() .  '_invalid' . '.csv'
				];

				#active
				$activeFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['dnc']);
				$activeFile = fopen($activeFilePath, 'w');
				fputcsv($activeFile, ['phone', 'federal', 'litigator', 'internal', 'wireless']);
				fclose($activeFile);

				#inactive
				$inactiveFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['non_dnc']);
				$inactiveFile = fopen($inactiveFilePath, 'w');
				fputcsv($inactiveFile, ['phone']);
				fclose($inactiveFile);
				//dd($data);
				// Invalid dnc file
				$invalidFilePath = Storage::disk('dnc-seperated')->path($data['filenames']['invalid']);
				$invalidFile = fopen($invalidFilePath, 'w');
				fclose($invalidFile);
			}
			$dncExport = new DncExport();
			$dncExport = $dncExport->create([
				'name' => $request->name,
				'paths' => $data['filenames'],
				'user_id' => auth()->user()->id,
			]);
			$data['export_id'] = $dncExport->id;

			try {
				(new DNCSeperator($data))->queue($request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
			} catch (Exception $e) {
				dd($e);
			}
			session()->flash('success_msg', 'File has been Added successfully');
			return back();
		} catch (Exception $e) {
			Log::error($e);
			session()->flash('danger_msg', 'Server Error');
			return back();
		}
	}

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

	public function getPDF($id)
	{ 
		ini_set('max_execution_time', 180); //3 minutes
     	// $export = DncExport::findOrFail($id);
		$export = DncExport::select(['id', 'name', 'paths', 'status', 'active_count', 'scrubing_option', 'inactive_count', 'invalid_dnc_count', 'total_count', 'user_id', 'json_data', 'created_at'])
    	->findOrFail($id);
		$updated_at = DncExport::select(['updated_at'])->findOrFail($id);

		$user = $export->user;
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ipaddress = $_SERVER['REMOTE_ADDR'];

		$pdf = App::make('dompdf.wrapper');

		$logo = null;
		$logo = (!App::environment(['local', 'staging'])) ? asset('webtheme/img/logo.png') : asset('img/logo.png');
		$logo = base64_encode(file_get_contents($logo));

		// code written by rakesh
		$jsonData = json_decode($export->json_data, true);
		$total_count = 0;
		$active_count = 0;
		$inactive_count = 0;
		$invalid_dnc_count = 0;

		if ($export->scrubing_option === 'seperate') {

			foreach ($jsonData as $key => $value) {

				$active_count += $value['active_count'];

				$invalid_dnc_count += $value['invalid_dnc_count'];
			}

			$inactive_count = $export->total_count - $active_count;
		} else {

			$active_count = $jsonData['active_count'];
			$inactive_count = $jsonData['inactive_count'];
			$invalid_dnc_count = $jsonData['invalid_dnc_count'];
		}
		// update table
		$export->active_count = $active_count;
		$export->inactive_count = $inactive_count;
		$export->invalid_dnc_count = $invalid_dnc_count;
		$export->timestamps = false; // Disable automatic timestamps R
		$export->update([
			'active_count' => $active_count,
			'inactive_count' => $inactive_count,
			'invalid_dnc_count' => $invalid_dnc_count,
		]);
		// code written by rakesh
		$export->timestamps = true; // Re-enable automatic timestamps R
		$pdf = App::make('dompdf.wrapper');
		return $pdf->loadView('admin.scrub.scrubpdftemplate', [
			'user' => $user,
			'export' => $export,
			'logo' => $logo,
			'ipaddress' => $ipaddress,
			'updated_at' => $updated_at->updated_at,

		])->setPaper('A4')->stream();
	}
}
