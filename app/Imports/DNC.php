<?php

namespace App\Imports;

use App\Models\ClientDncList;
use App\Models\DncImport;
use App\Models\DncList;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use Log;

class DNC implements ToModel, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable, RegistersEventListeners;
    protected $data;
    private $length = 10;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function model(array $row)
    {
        $phone = $row[0];
        if (isset($phone) && !empty($phone) && is_numeric($phone)) {

            $data = $this->data['rowData'];
            $data = array_merge($data, [
                'phone_no' => $phone,
                'uploaded_by' => $this->data['user'],
                'modified_by' => $this->data['user'],
                'region_id' => $this->data['region_id']
            ]);
            $data['upload_path'] = '';
           // turn off this code by rakesh
            if ($this->data['client']) {
                $data['client_id'] = $this->data['user'];
                return new ClientDncList($data);
            } else
            return new DncList($data);
        }
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
                DncImport::find($this->data['import_id'])->update(['status' => 'processed']);
                session()->flash('success_msg', 'Data Inserted Successfully');
            }
        ];
    }

    public function failed(Exception $exception): void
    {
        DncImport::find($this->data['import_id'])->update(['status' => 'failed']);
    }
}
