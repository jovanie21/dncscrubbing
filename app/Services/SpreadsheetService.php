<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class SpreadsheetService
{

    private $spreadsheet;
    private $sheetNames;
    private $writer;
    private $writerType = 'Xlsx';
    private $filename;
    private $worksheet;
    private $data;
    public  $filePath;
    private $headers = [];

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function setSheetNames($sheetNames)
    {
        $this->sheetNames = $sheetNames;
        return $this;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function create()
    {
        // Create a new Spreadsheet
        $this->spreadsheet = new Spreadsheet();
        // Iterate over sheet names and create sheets
        foreach ($this->sheetNames as $sheetName) {
            // Create a new sheet
            $worksheet = $this->spreadsheet->createSheet();
            $worksheet->setTitle($sheetName);
        }

        // Save the data to a CSV file
        // Adjust the filename and format (e.g., .csv) as needed
        $this->writer = IOFactory::createWriter($this->spreadsheet, $this->writerType);
        $this->writer->save($this->filePath);

        $this->spreadsheet = null;
        $this->writer = null;

        return $this;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function writeHeader()
    {
        $this->spreadsheet = IOFactory::load($this->filePath);

        // remove default Wrokshet name by index
        $sheetIndex = $this->spreadsheet->getIndex(
            $this->spreadsheet->getSheetByName('Worksheet')
        );

        $this->spreadsheet->removeSheetByIndex($sheetIndex);

        // get sheet names
        $this->sheetNames = $this->spreadsheet->getSheetNames();

        // write header for each sheet
        //dd($this->headers);
        if ($this->sheetNames) {

            foreach ($this->sheetNames as $name) {
                // echo "<pre>";
                // print_r($this->headers[$name]);
                $this->worksheet = $this->spreadsheet->getSheetByName($name);
                $this->worksheet->fromArray($this->headers[$name]);
                ///$this->worksheet = null;
            }
        }


        $this->writer = IOFactory::createWriter($this->spreadsheet, $this->writerType);
        $this->writer->save($this->filePath);

        $this->spreadsheet = null;
        $this->writer = null;
        //exit;
        return $this;
    }

    public function writeData()
    {
        $this->spreadsheet = IOFactory::load($this->filePath);
        // // remove default Wrokshet name by index
        // $sheetIndex = $this->spreadsheet->getIndex(
        //     $this->spreadsheet->getSheetByName('Worksheet')
        // );

        // $this->spreadsheet->removeSheetByIndex($sheetIndex);

        // get sheet names
        $this->sheetNames = $this->spreadsheet->getSheetNames();


        Log::info("service log");
        Log::info($this->sheetNames);
        Log::info("service log");

        if ($this->data) {
            // write data to each sheet
            foreach ($this->sheetNames as $name) {
                if (array_key_exists($name, $this->data)) {
                    $this->worksheet = $this->spreadsheet->getSheetByName($name);
                    $this->worksheet->fromArray($this->data[$name]);


                    $this->writer = IOFactory::createWriter($this->spreadsheet, $this->writerType);
                    $this->writer->save($this->filename);

                    $this->spreadsheet = null;
                    $this->writer = null;
                    $this->worksheet = null;
                }
            }
        }
    }
}
