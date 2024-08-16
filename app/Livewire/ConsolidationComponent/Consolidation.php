<?php

namespace App\Livewire\ConsolidationComponent;

use App\Models\F11Model;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Illuminate\Support\Facades\Schema;
use App\Models\LocalAreaListingsModel;

#[Title('Consolidation')]
class Consolidation extends Component
{
    use WithFileUploads;

    #[Validate('file|mimes:zip|max:1024')]
    public $ConsolidationFile;

    public function ConsolidationSave()
    {
        try {
            // Ensure a file is uploaded
            if (!$this->ConsolidationFile) {
                throw new \Exception('No file uploaded.');
            }

            // Store the uploaded zip file temporarily
            $path = $this->ConsolidationFile->store('temp');

            // Get the full path of the uploaded file
            $fullPath = Storage::path($path);

            // Initialize ZipArchive to open the zip file
            $zip = new ZipArchive;
            if ($zip->open($fullPath) !== TRUE) {
                throw new \Exception('Failed to open the zip file.');
            }

            // Extract the contents of the zip file to a temp directory
            $extractPath = storage_path('app/temp/unzipped/');
            $zip->extractTo($extractPath);
            $zip->close();

            // Get all CSV files from the extracted directory
            $csvFiles = glob($extractPath . '*.csv');

            foreach ($csvFiles as $csvFile) {
                // Read the contents of the CSV file
                $csvData = array_map('str_getcsv', file($csvFile));
                if (empty($csvData)) {
                    throw new \Exception('CSV file is empty.');
                }

                // Extract the header and data
                $header = $csvData[0];
                $rows = array_slice($csvData, 1);

                // Check the filename to determine the target table
                $filename = basename($csvFile);
                $tableName = null;
                if (strpos($filename, 'f11') === 0) {
                    $tableName = 'f11_conso';
                } elseif (strpos($filename, 'localarea_listings') === 0) {
                    $tableName = 'localarea_listings_conso';
                }

                if ($tableName) {
                    // Get the columns of the target table
                    $tableColumns = Schema::getColumnListing($tableName);

                    // Check if CSV header matches the table structure
                    if ($header !== $tableColumns) {
                        throw new \Exception("CSV file structure does not match the $tableName table structure.");
                    }

                    // Prepare the data for insertion
                    $insertData = [];
                    foreach ($rows as $row) {
                        $rowData = array_combine($header, $row);
                        $insertData[] = $rowData;
                    }

                    dd($rowData);
                    // if ($tableName == 'localarea_listings_conso') {
                    //     LocalAreaListingsModel::insert($insertData);
                    // } else {
                    //     F11Model::insert($insertData);
                    // }

                    // Insert the data into the table

                } else {
                    // Log or dispatch an error if the filename doesn't match expected patterns
                    Log::error("Unrecognized file pattern: {$filename}");
                }
            }

            // Clean up temporary files
            Storage::deleteDirectory('temp/unzipped/');
            Storage::delete($path);
        } catch (\Exception $e) {
            // Handle any errors that occur
            Log::error('Error in ConsolidationSave: ' . $e->getMessage());
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.consolidation-component.consolidation');
    }
}
