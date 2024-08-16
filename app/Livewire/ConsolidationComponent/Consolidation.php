<?php

namespace App\Livewire\ConsolidationComponent;

use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

#[Title('Consolidation')]
class Consolidation extends Component
{
    use WithFileUploads;

    #[Validate('file|mimes:zip|max:1024')]
    public $ConsolidationFile;

    public function ConsolidationSave()
    {
        // Ensure a file is uploaded
        if ($this->ConsolidationFile) {

            // Store the uploaded zip file temporarily
            $path = $this->ConsolidationFile->store('temp');

            // Get the full path of the uploaded file
            $fullPath = Storage::path($path);

            // Initialize ZipArchive to open the zip file
            $zip = new ZipArchive;
            if ($zip->open($fullPath) === TRUE) {

                // Extract the contents of the zip file to a temp directory
                $extractPath = storage_path('app/temp/unzipped/');
                $zip->extractTo($extractPath);
                $zip->close();

                // Get all CSV files from the extracted directory
                $csvFiles = glob($extractPath . '*.csv');

                // Initialize an array to hold the contents of each CSV file
                $csvFileNames = [];

                foreach ($csvFiles as $csvFile) {
                    $csvFileName = basename($csvFile);
                    $csvFileNames[] = $csvFileName;
                    // Read the contents of the CSV file
                    $csvData = array_map('str_getcsv', file($csvFile));
                }

                // Dump all relevant variables and the extracted CSV data
                // dd([
                //     'ConsolidationFile' => $this->ConsolidationFile,
                //     'ExtractedFiles' => $csvFiles,
                //     'CSVData' => $csvData,
                //     'Filenames' => $csvFileNames,
                // ]);

                // Determine the table name based on file names
                foreach ($csvFileNames as $fileName) {
                    if (strpos($fileName, 'f11') !== false) {
                        $tableName = 'f11_conso';
                        break;
                    } elseif (strpos($fileName, 'localarea_listings') !== false) {
                        $tableName = 'localarea_listings_conso';
                        break;
                    }
                }

                // Dump the table name for debugging
                dd(['TableName' => $tableName]);
            } else {
                // If the zip file cannot be opened, throw an error
                dd('Failed to open the zip file.');
            }
        } else {
            // No file was uploaded
            dd('No file uploaded.');
        }
    }

    public function render()
    {
        return view('livewire.consolidation-component.consolidation');
    }
}
