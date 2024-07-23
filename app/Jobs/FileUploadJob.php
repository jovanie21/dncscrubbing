<?php

namespace App\Jobs;

use App\Models\AdminScrubUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Models\DncList;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Auth;
use DB;

class FileUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileContents;
    protected $filename;
    protected $request;
    protected $serialize = ['fileContents', 'filename', 'request'];

    /**
     * Create a new job instance.
     *
     * @param string $fileContents
     * @param string $filename
     */
    public function __construct(string $fileContents, string $filename ,$request)
    {
        $this->fileContents = $fileContents;
        $this->filename = $filename;
        $this->request = $request;
    }

    /**
     * Execute the job. 284054 - 178 3151411
     *
     * @return void
     */
    public function handle()
    { 
        // die('lll');
        // print_r(Auth::user()->id);die('kkk');  
        // $file_data = file_get_contents($file);
        $phoneNumbers = explode("\n", $this->fileContents);
         // $phoneNumbers = explode("\n", $file_data);
        $phoneNumbers = array_filter($phoneNumbers); // remove empty elements
        $phoneNumbers = array_map('trim', $phoneNumbers);

        // Prepare filenames for the DNC and non-DNC files
        $current_date_time = Carbon::now()->format('YmdHis');

        $dnc_file_name = 'dnc' . $current_date_time . '.csv';
        $dncFilePath = 'dnc_file/' . $dnc_file_name;
        $non_dnc_file_name = 'non_dnc' . $current_date_time . '.csv';
        $nonDncFilePath = 'non_dnc_file/' . $non_dnc_file_name;

        $filepath = 'uploads/adminFiles/scrub-' . $current_date_time;
        // Open the CSV files for writing
        $dnc_file = fopen($dnc_file_name, 'w');
        $non_dnc_file = fopen($non_dnc_file_name, 'w');
        $dnc_count = 0;
        $non_dnc_count = 0;
        // Loop through the phone numbers and check if they are in the DNC list
        foreach ($phoneNumbers as $phoneNumber) {
            $phoneNumber = trim($phoneNumber);
            $is_dnc = DB::table('dnc_lists')->where('phone_no', $phoneNumber)->exists();
            // Write the phone number to the appropriate CSV file
            if ($is_dnc) {
                fputcsv($dnc_file, [$phoneNumber]);
                $dnc_count++;
            } else {
                fputcsv($non_dnc_file, [$phoneNumber]);
                $non_dnc_count++;
            }
        }

        // Close the CSV files
        fclose($dnc_file);
        fclose($non_dnc_file);

        // Upload the files to S3
        $s3 = Storage::disk('s3');
        $s3->put('dnc_file/' . $dnc_file_name, file_get_contents($dnc_file_name));
        $s3->put('non_dnc_file/' . $non_dnc_file_name, file_get_contents($non_dnc_file_name));

        // Delete the local CSV files
        unlink($dnc_file_name);
        unlink($non_dnc_file_name);
        // $data = AdminScrubUpload::find($scrub_upload_id);
        $data = new AdminScrubUpload;
        $data->admin_id = Auth::user()->id;
        $data->is_processed = 2;
        $data->is_dump = 1;
        $data->is_deleted = 1;
        $data->upload_name = $this->filename;
        $data->unidentical_file_path = $nonDncFilePath;
        $data->identical_file_path = $dncFilePath;
        $data->file_path = $filepath;
        $data->dnc_count = $dnc_count;
        $data->non_dnc_count = $non_dnc_count;
        $data->total_rows = count($phoneNumbers);
        $data->save();
        // return back();
    }

    public function getJobId()
    {
        return $this->job->getJobId();
    }
}
