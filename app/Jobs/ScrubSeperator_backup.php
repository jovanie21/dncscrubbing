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
use App\Models\ClientDncList;
use App\Models\DncExport;
use App\Models\DncList;

use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Storage;
use App\Models\Region;


class ScrubSeperator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $filename;
    public $export_id;
    public $user_id;
    public $rowData;
    public $count;

    public function __construct($data, $filename, $export_id, $user_id)
    {
        // dd($data);
        $this->filename = $filename;
        $this->data = $data;
        $this->export_id = $export_id;
        $this->user_id =  $user_id;
        $this->rowData = empty($data['rowData']) ? array() : $data['rowData'];
        $this->count = count($this->rowData);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $filename = $this->filename;
            $data = $this->data;
            $path = public_path('scrubupload/' . $filename);
            $rows = SimpleExcelReader::create($path, 'csv')
                ->noHeaderRow()
                ->getRows();
            $all_rows = array_merge(...json_decode($rows, true));
            $numbers = array_unique($all_rows);
            $export = DncExport::find($this->export_id);
            $activeDNC = array();
            ///////////////////////////////////////
            if ($data['option'] == 'combined') {
                foreach (array_chunk($numbers, 999) as $num) {
                    if ($data['type'] == 'internal') {
                        $q = ClientDncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless'])
                            ->where(['client_id' => $this->user_id]);
                    } else {
                        $q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless']);
                    }

                    if ($data['is_region']) {
                        $q = $q->whereIn('region_id', array_keys($data['regions']));
                    }
                    $q = $q->whereIn('phone_no', $num);
                    if ($this->count == 1) {
                        $q = $q->where($this->rowData[0], 'yes');
                    } elseif ($this->count == 2) {
                        $q = $q->where(function ($query) {
                            $query->orwhere($this->rowData[0], 'yes')
                                ->orwhere($this->rowData[1], 'yes');
                        });
                    } elseif ($this->count == 3) {
                        $q = $q->where(function ($query) {
                            $query->orwhere($this->rowData[0], 'yes')
                                ->orwhere($this->rowData[1], 'yes')
                                ->orwhere($this->rowData[2], 'yes');
                        });
                    }

                    $activeDNC[] = $q->get()->toArray();
                }

                $activeDNC = array_merge(...$activeDNC);
                $this->process($all_rows, $activeDNC, $export);
            } else {

                $regions = $data['regions'];
                foreach ($regions as $id => $region) {
                    $activeDNC = array();
                    foreach (array_chunk($numbers, 999) as $num) {
                        if ($data['type'] == 'internal') {
                            $q = ClientDncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless'])->whereIn('phone_no', $num)->where(['client_id' => $this->user_id, 'region_id' => $id]);
                        } else {
                            $q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless'])->whereIn('phone_no', $num)->where(['region_id' => $id]);
                        }


                        if ($this->count == 1) {
                            $q = $q->where($this->rowData[0], 'yes');
                        } elseif ($this->count == 2) {
                            $q = $q->where(function ($query) {
                                $query->orwhere($this->rowData[0], 'yes')
                                    ->orwhere($this->rowData[1], 'yes');
                            });
                        } elseif ($this->count == 3) {
                            $q = $q->where(function ($query) {
                                $query->orwhere($this->rowData[0], 'yes')
                                    ->orwhere($this->rowData[1], 'yes')
                                    ->orwhere($this->rowData[2], 'yes');
                            });
                        }


                        $activeDNC[] = $q->get()->toArray();
                    }
                    $activeDNC = array_merge(...$activeDNC);
                    $this->process($all_rows, $activeDNC, $export, $id);
                }
            }
            ///////////////////////////////////////
            $export->status = 'processed';
            $export->save();
            unlink($path);
        } catch (Exception $e) {
            DncExport::find($this->export_id)->update(['status' => 'failed']);
            $path = public_path('scrubupload/' . $this->filename);
            unlink($path);
            Log::error($e->getMessage());
        }
    }

    public function failed(\Throwable $e): void
    {
        DncExport::find($this->export_id)->update(['status' => 'failed']);
        $path = public_path('scrubupload/' . $this->filename);
        unlink($path);
        Log::error($e->getMessage());
    }

    public function process($numbers, $activeDNC, $export, $regionId = null)
    {
        $uploadPath = $regionId ? $this->data['filenames'][$regionId] : $this->data['filenames'];
        $inActiveDNC = array_values(array_diff($numbers, array_column($activeDNC, 'phone')));
        $export->active_count += count($activeDNC);
        $export->inactive_count += count($inActiveDNC);
        $export->save();
        if (count($activeDNC)) {
            $path = Storage::disk('dnc-seperated')->path($uploadPath['active']);
            $file = fopen($path, 'a');
            foreach ($activeDNC as $fields) {
                fputcsv($file, $fields);
            }
            fclose($file);
        }
        if (count($inActiveDNC)) {
            $path = Storage::disk('dnc-seperated')->path($uploadPath['inactive']);
            $file = fopen($path, 'a');
            foreach ($inActiveDNC as $fields) {
                fputcsv($file, [$fields]);
            }
            fclose($file);
        }
    }
}
