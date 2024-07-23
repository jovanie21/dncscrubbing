<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\DncImport;
use Log;
// use Exception;

class DncListUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
     public $rowData;
      public $rowData1;
      public $dncImport_id;
      public $filename;
      public $table_name;

    public function __construct($rowData,$filename,$dncImport_id,$table_name)
    {
        $this->filename = $filename;
        $this->rowData = $rowData;
        $this->dncImport_id = $dncImport_id;
        $this->table_name = $table_name;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
          $filename = $this->filename;
          $rowData = $this->rowData;
          $table_name =$this->table_name;
          $dncImport_id = $this->dncImport_id;
          $path = public_path('dncupload/'.$filename);
          $rows = SimpleExcelReader::create($path,'csv')
          ->noHeaderRow()
          ->getRows();
          $all_rows =array_merge(...json_decode($rows,true));
          $result = array();
          foreach($all_rows as $number)
          {
            $result[] = array_merge($rowData,['phone_no' =>$number]);
          }
         
          foreach(array_chunk($result,6000) as $resilt2)
          {
            DB::table($table_name)->insert($resilt2); 
          }
          DncImport::find($dncImport_id)->update(['status' => 'processed']);
          unlink($path);
        }catch(Exception $e)
        {
          DncImport::find($dncImport_id)->update(['status' => 'failed']);
          unlink($path);
          Log::error($e->getMessage());
        }
       
    }

    public function failed(\Throwable $e): void
    {
        DncImport::find($this->dncImport_id)->update(['status' => 'failed']);
        $path = public_path('dncupload/'.$this->filename);
        unlink($path);
        Log::error($e->getMessage());
    }
}
