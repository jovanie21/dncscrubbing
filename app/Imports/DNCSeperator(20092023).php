<?php

namespace App\Imports;

use App\Models\ClientDncList;
use App\Models\DncExport;
use App\Models\DncList;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;

class DNCSeperator implements ToCollection, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable, RegistersEventListeners;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(Collection $rows)
    {
        $data = $this->data;
        $numbers = array_column($rows->toArray(), 0);
        $export = DncExport::find($this->data['export_id']);
        if ($data['option'] == 'combined') {
            if ($data['type'] == 'internal')
                $q = ClientDncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless'])->where(['client_id' => $data['user']]);

            else
                $q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless']);
                
            if ($data['is_region']) {
                $q = $q->whereIn('region_id', array_keys($data['regions']));
            }
            if (in_array('wireless', $data['rowData'])) {
                $q = $q->where('wireless', 'yes');
            }

            // Check if "litigator" exists in the "rowData" array and add a condition if true
            if (in_array('litigator', $data['rowData'])) {
                $q = $q->where('litigator', 'yes');
            }

            // Check if "federal" exists in the "rowData" array and add a condition if true
            if (in_array('federal', $data['rowData'])) {
                $q = $q->where('federal', 'yes');
            }
            $q = $q->whereIn('phone_no', $numbers);
            $activeDNC = $q->distinct()->get()->toArray();
            $this->process($numbers, $activeDNC, $export);
        } else {
            $regions = $this->data['regions'];
            foreach ($regions as $id => $region) {
                if ($data['type'] == 'internal')
                    $activeDNC = ClientDncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless'])->where(['client_id' => $data['user']]);
                else
                    $activeDNC = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless']);

                if ($data['is_region']) {
                    $activeDNC = $activeDNC->whereIn('region_id', array_keys($data['regions']));
                }
                if (in_array('wireless', $data['rowData'])) {
                    $activeDNC = $activeDNC->where('wireless', 'yes');
                }

                // Check if "litigator" exists in the "rowData" array and add a condition if true
                if (in_array('litigator', $data['rowData'])) {
                    $activeDNC = $activeDNC->where('litigator', 'yes');
                }

                // Check if "federal" exists in the "rowData" array and add a condition if true
                if (in_array('federal', $data['rowData'])) {
                    $activeDNC = $activeDNC->where('federal', 'yes');
                }
                $activeDNC = $activeDNC->whereIn('phone_no', $numbers);
                $activeDNC = $activeDNC->distinct('phone_no')->get()->toArray();
                $this->process($numbers, $activeDNC, $export, $id);
            }
        }
        return $rows;
    }

    public function chunkSize(): int
    {
        return 2000;
    }

    public function batchSize(): int
    {
        return 2000;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                DncExport::find($this->data['export_id'])->update(['status' => 'processed']);
                session()->flash('success_msg', 'Data Checing Completed');
            }
        ];
    }

    public function failed(): void
    {
        DncExport::find($this->data['export_id'])->update(['status' => 'failed']);
    }

    public function process($numbers, $activeDNC, $export, $regionId = null)
    {
        $uploadPath = $regionId ? $this->data['filenames'][$regionId] : $this->data['filenames'];
        $inActiveDNC = array_diff($numbers, array_column($activeDNC, 'phone'));
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
