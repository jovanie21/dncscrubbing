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
    protected $count;
    protected $rowData;

    public function __construct($data)
    {
        $this->data = $data;
        $this->rowData = empty($data['rowData']) ? array() : $data['rowData'];
        $this->count = count($this->rowData);
        // dd($data);
        // $q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless']);
        // $in = ['3303491129'];
        // if ($data['is_region']) {
        //     $q = $q->whereIn('region_id', array_keys($data['regions']));
        // }
        // if ($this->count == 1) {
        //     $q = $q->where($this->rowData[0], 'yes');
        // } elseif ($this->count == 2) {
        //     $q = $q->where(function ($query) {
        //         $query->orwhere($this->rowData[0], 'yes')
        //             ->orwhere($this->rowData[1], 'yes');
        //     });
        // } elseif ($this->count == 3) {
        //     $q = $q->where(function ($query) {
        //         $query->orwhere($this->rowData[0], 'yes')
        //             ->orwhere($this->rowData[1], 'yes')
        //             ->orwhere($this->rowData[2], 'yes');
        //     });
        // }
        // $q = $q->whereIn('phone_no', $in);
        // $activeDNC = $q->distinct('phone_no')->get()->toArray();
        // dd($activeDNC);

            /*
        $collection = collect([
            7735692586,
            7402198500,
            3303491129,
            7406820472,
            4402652071,
            5088660818,
            5082918020,
            3097551645,
            7186201501,
            5854589329,
            7185853236,
            7183643257,
            7404525080,
            7733056000,
            7406221419,
            7736271403,
            6309651999,
            7403853173,
            3129785276,
            3096489307,
            8152749537,
            7732803749,
            3129830142,
            8152129019,
            7408764257,
            7733662703,
            2163248470,
            2163248411,

        ]);
        //dd($collection);
        dd($this->collection($collection));
        */
    }

    public function collection(Collection $rows)
    {
        $data = $this->data;

        //$numbers = $rows->toArray(); //array_column($rows->toArray(), 0);
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
            // if ($this->count == 1) {
            //     $q = $q->where($this->rowData[0], 'yes');
            // } elseif ($this->count == 2) {
            //     $q = $q->where(function ($query) {
            //         $query->orwhere($this->rowData[0], 'yes')
            //             ->orwhere($this->rowData[1], 'yes');
            //     });
            // } elseif ($this->count == 3) {
            //     $q = $q->where(function ($query) {
            //         $query->orwhere($this->rowData[0], 'yes')
            //             ->orwhere($this->rowData[1], 'yes')
            //             ->orwhere($this->rowData[2], 'yes');
            //     });
            // }
            $q = $q->whereIn('phone_no', $numbers);
            $activeDNC = $q->distinct('phone_no')->get()->toArray();

            // Initialize an empty associative array to store the result
            $result = [];
          
            // Iterate through the data and rearrange it
            foreach ($activeDNC as $row) {
                $phone = $row['phone'];
                $federal = $row['federal'];
                $litigator = $row['litigator'];
                $internal = $row['internal'];
                $wireless = $row['wireless'];

                // If the phone number is not in the result array, create a new entry
                if (!isset($result[$phone])) {
                    $result[$phone] = [
                        'phone' => $phone,
                        'federal' => 'no',
                        'litigator' => 'no',
                        'internal' => 'no',
                        'wireless' => 'no'
                    ];
                }

                // Update the values based on the current row
                $result[$phone]['federal'] = ($federal === 'yes') ? 'yes' : $result[$phone]['federal'];
                $result[$phone]['litigator'] = ($litigator === 'yes') ? 'yes' : $result[$phone]['litigator'];
                $result[$phone]['internal'] = ($internal === 'yes') ? 'yes' : $result[$phone]['internal'];
                $result[$phone]['wireless'] = ($wireless === 'yes') ? 'yes' : $result[$phone]['wireless'];
            }

            $activeDNC = $result;
            $this->process($numbers, $activeDNC, $export);
        } else {
            $regions = $this->data['regions'];
            foreach ($regions as $id => $region) {
                if ($data['type'] == 'internal')
                    $q = ClientDncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless'])->where(['client_id' => $data['user']]);
                else
                    $q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless']);

                $q = $q->where('region_id', $id);



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
                $q = $q->whereIn('phone_no', $numbers);
                $activeDNC = $q->distinct('phone_no')->get()->toArray();
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

        
        $export->active_count = count($activeDNC);
        $export->inactive_count = count($inActiveDNC);

       // dd($export);
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
