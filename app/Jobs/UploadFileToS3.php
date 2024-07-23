<?php

namespace App\Jobs;

use App\Models\AdminScrubUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Models\DncList;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// use Auth;
use DB;

class UploadFileToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $adminScrubUpload;
    protected $fileContents;
    protected $filename;
    protected $adminId;
    protected $adminScrubUploadId;

    public function __construct($fileContents, $filename,$adminId)
    {
        $this->fileContents = $fileContents;
        $this->filename = $filename;
        $this->adminId = $adminId;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public $tries = 5; // Allow up to 3 attempts

    public $timeout = 3900; // Set a timeout of 1 hour (3600 seconds)

    public function handle(){
        $phoneNumbers = explode("\n", $this->fileContents);
        $phoneNumbers = array_map('trim', $phoneNumbers);
        $phoneNumbers = array_filter($phoneNumbers);

        // Prepare filenames for the DNC and non-DNC files
        $currentDateTime = Carbon::now()->format('YmdHis');
        $dncFileName = 'dnc' . $currentDateTime . '.csv';
        $nonDncFileName = 'non_dnc' . $currentDateTime . '.csv';
        $dncFilePath = 'dnc_file/' . $dncFileName;
        $nonDncFilePath = 'non_dnc_file/' . $nonDncFileName;

        $current_date_time = Carbon::now()->format('YmdHis');
        $filepath = 'uploads/adminFiles/scrub-' . $current_date_time;

        // Open the CSV files for writing
        $dncFile = fopen($dncFileName, 'w');
        $nonDncFile = fopen($nonDncFileName, 'w');

        // Initialize counters
        $dncCount = 0;
        $nonDncCount = 0;
        // $processedNumbers = [];

        // Loop through the phone numbers and check if they are in the DNC list
        foreach ($phoneNumbers as $phoneNumber) {
            $phoneNumber = str_replace(',', '', $phoneNumber);

            // Skip processing if the number is already encountered
            // if (in_array($phoneNumber, $processedNumbers)) {
            //     continue;
            // }

            // // Mark the number as processed
            // $processedNumbers[] = $phoneNumber;

            $isDnc = DB::table('dnc_lists')->where('phone_no', $phoneNumber)->first();

            // Write the phone number to the appropriate CSV file
            if ($isDnc) {
                fputcsv($dncFile, [$phoneNumber]);
                $dncCount++;
            } else {
                fputcsv($nonDncFile, [$phoneNumber]); 
                $nonDncCount++;
            }
        }

        // Close the CSV files
        fclose($dncFile);
        fclose($nonDncFile);

        // Upload the files to S3
        $s3 = Storage::disk('s3');
        $s3->put($dncFilePath, file_get_contents($dncFileName));
        $s3->put($nonDncFilePath, file_get_contents($nonDncFileName));

        // Delete the local CSV files
        unlink($dncFileName);
        unlink($nonDncFileName);

        // Save the data to the database
        $data = new AdminScrubUpload;
        $data->admin_id = $this->adminId;
        $data->is_processed = 2;
        $data->is_dump = 1;
        $data->is_deleted = 1;
        $data->upload_name = $this->filename;
        $data->unidentical_file_path = $nonDncFilePath;
        $data->identical_file_path = $dncFilePath;
        $data->file_path = $filepath;
        $data->dnc_count = $dncCount;
        $data->non_dnc_count = $nonDncCount;
        $data->total_rows = count($phoneNumbers);
        $data->save();
}

}
