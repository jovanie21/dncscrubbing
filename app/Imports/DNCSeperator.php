<?php

namespace App\Imports;

use App\Models\ClientDncList;
use App\Models\DncExport;
use App\Models\DncList;
use App\Models\Region;
use App\Models\RegionType;
use App\Services\SpreadsheetService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DNCSeperator implements ToCollection, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable, RegistersEventListeners;
    protected $data;
    protected $count;
    protected $rowData;
    private $dncList = [];
    private $nonDncList = [];
    private $numbers;
    private $export;
    private $regionId = null;
    private $validNumbers  = [];
    private $invalidNumbers = [];
    private $length = 0;
    private $returnTypeOf;
    private $uploadPath;
    private $dncListArray;
    private $regionIds;
    private $result;
    private $newPaths;

    private $activeCount = 0;
    private $invalidDncCount = 0;
    private $inactiveCount = 0;
    private $totalCount = 0;
    private $i = 0;
    private $scrub;
    private $regions;
    private $region;
    private $combinedCsvFile;
    private $combinedCsvFileSpreadsheet;
    private $dncCsvFile;
    private $nonDncCsvFile;
    private $dncCsvFileSpreadsheet;
    private $nonDncCsvFileSpreadsheet;
    private $combinedCsvFileDncSpreadsheet;
    private $combinedCsvFileNonDncSpreadsheet;
    private $reader;
    private $writer;
    private $sheetData = [];
    private $sheetNames = [];
    private $seperateUniqueNonDncList;

    public function __construct($data)
    {
        ini_set('memory_limit', '-1');
        $this->data = $data;
        $this->rowData = empty($data['rowData']) ? array() : $data['rowData'];
        $this->count = count($this->rowData);

        $this->spreadSheetService = new SpreadsheetService();

        $this->uploadPath = $this->data['filenames'];
    }

    private function _setResult()
    {
        $q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless', 'region_id']);

        if (!empty($this->rowData) && isset($this->rowData)) {
            // if selected scrub types are more then one then we added orWhere clause to query like, (interel = true || federal  = false) liek that
            if (count($this->rowData) > 1) {
                $q = $q->where(function ($query) {
                    return $query->orwhere($this->rowData);
                });
            } else {

                // in else part if user select only one scrub type then we adeed where clause onyl like internal/federal/wireless/etc = true
                $q = $q->where($this->rowData);
            }
        }

        // add where in clause if user is selected specific dnc list from dropdown
        if ($this->data['is_region']) {
            $this->regionIds = array_keys($this->data['regions']);
            $q = $q->whereIn('region_id', array_keys($this->data['regions']));

            $this->regions = Region::whereIn('id', $this->regionIds)->get()->toArray();
        } else {
            // if select any scrub type then get all regions id from regions table by type that joing with regions_types table
            // and add where in clouse
            $regionIds = RegionType::select('region_id')->whereIn('type', array_keys($this->rowData))->pluck('region_id')->toArray();
            $this->regionIds = array_values($regionIds);
            $this->regions = Region::whereIn('id', $this->regionIds)->get()->toArray();
            $q = $q->whereIn('region_id', $regionIds);
        }

        // info("====regionIds");
        // info($this->regionIds);
        // info("====regionIds");

        $q = $q->whereIn('phone_no', $this->numbers);

        // Initialize an empty associative array to store the result
        $this->result = [];
        $this->dncList = $q->distinct('phone')->get()->toArray();


        // echo

        if (!empty($this->dncList)) {
            // Iterate through the data and rearrange it
            foreach ($this->dncList as $row) {

                $phone = $row['phone'];
                $federal = $row['federal'];
                $litigator = $row['litigator'];
                $internal = $row['internal'];
                $wireless = $row['wireless'];
                // If the phone number is not in the this->result array, create a new entry
                if (!isset($this->result[$phone])) {
                    $this->result[$phone] = [
                        'phone' => (int) $phone,
                        'federal' => 'no',
                        'litigator' => 'no',
                        'internal' => 'no',
                        'wireless' => 'no',
                        'region_id' => $row['region_id']
                    ];
                }
                // Update the values based on the current row
                $this->result[$phone]['federal'] = ($federal === 'yes') ? 'yes' : $this->result[$phone]['federal'];
                $this->result[$phone]['litigator'] = ($litigator === 'yes') ? 'yes' : $this->result[$phone]['litigator'];
                $this->result[$phone]['internal'] = ($internal === 'yes') ? 'yes' : $this->result[$phone]['internal'];
                $this->result[$phone]['wireless'] = ($wireless === 'yes') ? 'yes' : $this->result[$phone]['wireless'];
                $this->result[$phone]['region_id'] = $row['region_id'];
            }

            $regionsResult = [];
            if ($this->data['option'] === 'seperate') {

                $this->seperateUniqueNonDncList = $this->result;

                if ($this->regions) {
                    foreach ($this->regions as $region) {

                        $regionsResult[$region['id']] = array_filter($this->result, function ($result, $k) use ($region) {
                            return ($region['id'] === $result['region_id']);
                        }, ARRAY_FILTER_USE_BOTH);
                    }
                }
            }


            if (!empty($regionsResult)) {


                $this->result = [];
                $this->result = $regionsResult;
            }
        }
    }



    private function _setDncAndNonDncList()
    {
        // filter dnc list scrub type wise

        // Gets valid and invalid numbers from uploaded csv 
        foreach ($this->numbers as $number) {
            // Checks whether the number is numeric and number length is 10 or not?
            if (!empty($number) && strlen($number)) {
                if (!is_numeric($number) && (int) strlen($number) !== $this->length)
                    $this->invalidNumbers[] = $number;
                else
                    $this->validNumbers[] = $number;
            }
        }

        // $this->validNumbers = new number + federal + non region numbers
        // get dnc list
        $this->dncListArray = array_column($this->dncList, 'phone');

        // get clean records or non dnc list
        $this->nonDncList = array_filter(array_unique($this->validNumbers), function ($v, $k) {
            return !in_array($v, $this->dncListArray);
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function _saveSeperateNonDnc()
    {
        $this->dncList = [];

        if (!empty($this->seperateUniqueNonDncList)) {
            $this->dncList = $this->seperateUniqueNonDncList;
        }

        $this->_setDncAndNonDncList();


        if (!empty($this->nonDncList) && count($this->nonDncList) > 0) {
            $path = Storage::disk('dnc-seperated')->path($this->uploadPath['non_dnc']);
            $file = fopen($path, 'a');
            foreach ($this->nonDncList as $fields) {
                fputcsv($file, [$fields]);
            }
            fclose($file);
        }
        $this->dncList = null;
        $this->nonDncList = null;
        $this->dncListArray = null;
    }



    private function _seperate()
    {
        if (!empty($this->export->json_data)) {
            $arr = json_decode($this->export->json_data, true);
        }

        foreach ($this->regions as $region) {
            // $this->region = $region;
            $this->regionId = $region['id'];

            if (!empty($this->result)) {
                $this->dncList = $this->result[$this->regionId];
            }
            // set dnc and non dnc list
            $this->_setDncAndNonDncList();
            $this->_reset();


            //$this->uploadPath = $this->regionId ? $this->data['filenames'][$this->regionId] : $this->data['filenames'];


            $this->activeCount          = ($this->dncListArray) ? count($this->dncListArray) : 0;
            $this->inactiveCount        = ($this->nonDncList) ? count($this->nonDncList) : 0;
            $this->invalidDncCount      = ($this->invalidNumbers) ? count($this->invalidNumbers) : 0;
            $this->totalCount           = ($this->numbers) ? count($this->numbers) : 0;

            if (!empty($arr)) {

                $this->activeCount += $arr[$this->regionId]['active_count'];
                $this->inactiveCount += $arr[$this->regionId]['inactive_count'];
                $this->invalidDncCount += $arr[$this->regionId]['invalid_dnc_count'];
                $this->totalCount += $arr[$this->regionId]['total_count'];
            }


            $this->scrub[$this->regionId] = [
                'active_count' => $this->activeCount,
                'inactive_count' => $this->inactiveCount,
                'invalid_dnc_count' => $this->invalidDncCount,
                'total_count' => $this->totalCount,
                'region_name' => $region['name']
            ];

            // $data[] = $this->nonDncList;
            $this->_writeSeperateCSV();
        }

        $this->_saveSeperateNonDnc();
    }
    private function _reset()
    {
        $this->activeCount          = 0;
        $this->inactiveCount        = 0;
        $this->invalidDncCount      = 0;
        $this->totalCount           = 0;
    }

    private function _writeSeperateCSV()
    {

        // create two files and write them
        if (!empty($this->dncList) && count($this->dncList) > 0) {
            $path = Storage::disk('dnc-seperated')->path($this->uploadPath['dnc']);
            $file = fopen($path, 'a');
            foreach ($this->dncList as $fields) {
                unset($fields['region_id']);
                fputcsv($file, $fields);
            }
            fclose($file);
        }
        if (!empty($this->invalidNumbers) && count($this->invalidNumbers) > 0) {
            $path = Storage::disk('dnc-seperated')->path($this->uploadPath['invalid']);
            $file = fopen($path, 'a');
            foreach ($this->invalidNumbers as $number) {
                fputcsv($file, [$number]);
            }
            fclose($file);
        } else {
            // Deletes if no invalid dnc found
            Storage::disk('dnc-seperated')->delete($this->uploadPath['invalid']);
        }
    }
    private function _combined()
    {



        if (!empty($this->export->json_data)) {
            $arr = json_decode($this->export->json_data, true);
        }

        if (!empty($this->result)) {
            $this->dncList = $this->result;
        }
        // set dnc and non dnc list
        $this->_setDncAndNonDncList();
        // $this->_reset();

        $this->activeCount          = ($this->dncListArray) ? count($this->dncListArray) : 0;
        $this->inactiveCount        = ($this->nonDncList) ? count($this->nonDncList) : 0;
        $this->invalidDncCount      = ($this->invalidNumbers) ? count($this->invalidNumbers) : 0;
        $this->totalCount           = ($this->numbers) ? count($this->numbers) : 0;

        if (!empty($arr)) {

            $this->activeCount += $arr['active_count'];
            $this->inactiveCount += $arr['inactive_count'];
            $this->invalidDncCount += $arr['invalid_dnc_count'];
            $this->totalCount += $arr['total_count'];
        }

        $this->scrub = [
            'active_count' => $this->activeCount,
            'inactive_count' => $this->inactiveCount,
            'invalid_dnc_count' => $this->invalidDncCount,
            'total_count' => $this->totalCount,
            'region_name' => ''
        ];

        $this->_writeCombinedCSV();

        // info($this->scrub);
    }

    private function _writeCombinedCSV()
    {
        // create two files and write them
        if (!empty($this->dncList) && count($this->dncList) > 0) {
            $path = Storage::disk('dnc-seperated')->path($this->uploadPath['dnc']);
            $file = fopen($path, 'a');
            foreach ($this->dncList as $fields) {
                unset($fields['region_id']);
                fputcsv($file, $fields);
            }
            fclose($file);
        }
        // create two files and write them
        if (!empty($this->nonDncList) && count($this->nonDncList) > 0) {
            $path = Storage::disk('dnc-seperated')->path($this->uploadPath['non_dnc']);
            $file = fopen($path, 'a');
            foreach ($this->nonDncList as $fields) {
                //unset($fields['region_id']);
                fputcsv($file, [$fields]);
            }
            fclose($file);
        }
        // if (!empty($this->invalidNumbers) && count($this->invalidNumbers) > 0) {
        //     $path = Storage::disk('dnc-seperated')->path($this->uploadPath['invalid']);
        //     $file = fopen($path, 'a');
        //     foreach ($this->invalidNumbers as $number) {
        //         fputcsv($file, [$number]);
        //     }
        //     fclose($file);
        // } else {
        //     // Deletes if no invalid dnc found
        //     Storage::disk('dnc-seperated')->delete($this->uploadPath['invalid']);
        // }
    }

    public function collection(Collection $rows)
    {


        //$numbers = $rows->toArray(); //array_column($rows->toArray(), 0);
        $this->numbers = array_column($rows->toArray(), 0);
        // info($this->numbers);
        $this->dncList = [];
        $this->export = DncExport::find($this->data['export_id']);

        $this->_setResult();

        $this->export->scrubing_option = $this->data['option'];

        if ($this->data['option'] == 'combined') {

            $this->_combined();
            $this->export->total_count = $this->totalCount;
            $this->export->json_data = json_encode($this->scrub);
            $this->export->save();
        } else {

            $this->_seperate();
            $this->export->total_count = $this->totalCount;
            $this->export->json_data = json_encode($this->scrub);
            $this->export->save();



            // $this->dncList = array_map(function() {

            // }, $this->dncList);
        }

        return $rows;
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function batchSize(): int
    {
        return 10000;
    }

    public function registerEvents(): array
    {
        // info('paths');
        // info($this->newPaths);
        // info('paths');
        return [
            AfterImport::class => function (AfterImport $event) {

                // info($event);
                $combineFile = time() .  '_dnc_non-dnc' . '.xlsx';
                $combniedFilePath = Storage::disk('dnc-seperated')->path($combineFile);

                if ($this->data['option'] == 'combined') {
                    
                    // Create a new Excel spreadsheet
                    $spreadsheet = new Spreadsheet();

                    foreach ($this->uploadPath as $sheetName => $filename) {
                        // Create a new sheet
                        $worksheet = $spreadsheet->createSheet();
                        $worksheet->setTitle($sheetName);
                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save($combniedFilePath);

                    $spreadsheet = null;
                    // load file
                    $spreadsheet = IOFactory::load($combniedFilePath);
                    // remove sheet
                    $sheetIndex = $spreadsheet->getIndex(
                        $spreadsheet->getSheetByName('Worksheet')
                    );
                    $spreadsheet->removeSheetByIndex($sheetIndex);


                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save($combniedFilePath);


                    foreach ($this->uploadPath as $sheetName => $filename) {
                        $filepath = Storage::disk('dnc-seperated')->path($filename);
                        // Load data from the CSV file
                        if (($handle = fopen($filepath, 'r')) !== false) {
                            $csvData = [];
                            while (($row = fgetcsv($handle)) !== false) {
                                $csvData[] = $row;
                            }
                            fclose($handle);
                        }
                        // write headers
                        $worksheet = $spreadsheet->getSheetByName($sheetName);
                        // Copy the data from the CSV file to the Excel worksheet
                        foreach ($csvData as $rowIndex => $rowData) {
                            foreach ($rowData as $columnIndex => $cellData) {
                                $worksheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 1, $cellData);
                            }
                        }

                        // Save the Excel spreadsheet to a new file
                        // $excelFilePath = 'output_excel_file.xlsx'; // Replace with the desired output file path
                        // $filepath = Storage::disk('dnc-seperated')->path($this->uploadPath['dnc']);

                    }

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    // Replace with the desired output file path
                    $writer->save($combniedFilePath);
                    
                    DncExport::find($this->data['export_id'])->update([
                        'status' => 'processed',
                        'paths' => [
                            'combined' => $combineFile
                        ],
                    ]);
                    session()->flash('success_msg', 'Data Checing Completed');
                }
                DncExport::find($this->data['export_id'])->update([
                    'status' => 'processed',
                ]);

                session()->flash('success_msg', 'Data Checing Completed');
            }
        ];
    }

    public function failed(): void
    {
        DncExport::find($this->data['export_id'])->update(['status' => 'failed']);
    }
}

