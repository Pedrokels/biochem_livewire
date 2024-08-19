<?php

namespace App\Livewire\ConsolidationComponent;

use App\Models\F11Model;
use App\Models\LocalAreaListingsModel;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

#[Title('Consolidation')]
class Consolidation extends Component
{
    use WithFileUploads;
    public $ConsolidationFile;

    #[Validate(['file|mimes:zip|max:1024'])]
    public function ConsolidationSave()
    {
        if (!$this->ConsolidationFile) {
            return;
        }
        // Store the uploaded zip file temporarily
        $path = $this->ConsolidationFile->store('temp');
        // Extract the contents of the zip file
        $zip = new \ZipArchive();
        $zip->open(storage_path('app/' . $path));
        $extractPath = storage_path('app/temp/extracted');
        $zip->extractTo($extractPath);
        $zip->close();

        // Initialize variables to store success or failure of insertions
        $insertSuccess = true;

        // Arrays to hold data for bulk insert
        $f11Data = [];
        $localAreaListingsData = [];

        // Loop through the extracted files
        foreach (glob($extractPath . '/*.csv') as $csvFile) {
            $fileName = basename($csvFile);

            // Read the CSV file data
            $csvData = array_map('str_getcsv', file($csvFile));

            // Skip the header row
            $header = array_shift($csvData);

            // Sanitize headers to remove any extra spaces
            $header = array_map('trim', $header);

            // Filter out empty headers
            $header = array_filter($header);

            // Insert the data into the corresponding table
            foreach ($csvData as $row) {
                // Ensure the row matches the header in length by padding with nulls
                $row = array_pad($row, count($header), null);

                // Combine header and row to create associative array
                $data = array_combine($header, $row);

                // Check for `id` and remove it if present
                if (isset($data['id'])) {
                    unset($data['id']);
                }
                if (isset($data['date_inserted'])) {
                    unset($data['date_inserted']);
                }

                // Separate data for F11Model and LocalAreaListingsModel
                if (str_starts_with($fileName, 'f11')) {
                    $f11Data[] = $data;
                } elseif (str_starts_with($fileName, 'localarea_listings')) {
                    $localAreaListingsData[] = $data;
                }
            }
        }

        // Perform bulk insert for F11Model
        try {
            $successConso = F11Model::insert($f11Data);
        } catch (\Exception $e) {
            $insertSuccess = false;
            dd('Insert failed for F11Model', $e->getMessage());
        }

        // Perform bulk insert for LocalAreaListingsModel
        try {
            $successConso = LocalAreaListingsModel::insert($localAreaListingsData);
        } catch (\Exception $e) {
            $insertSuccess = false;
            dd('Insert failed for LocalAreaListingsModel', $e->getMessage());
        }

        // Clean up the temporary files
        Storage::delete($path);
        File::deleteDirectory($extractPath);

        // Dump the result of the insertion
        if ($insertSuccess) {
            $this->dispatch('success-consolidation', success: $successConso);
            // dd('Insert successful');
        } else {
            dd('Insert failed');
        }
    }


    // public function ConsolidationSave()
    // {
    //     if (!$this->ConsolidationFile) {
    //         return;
    //     }

    //     // Store the uploaded zip file temporarily
    //     $path = $this->ConsolidationFile->store('temp');

    //     // Extract the contents of the zip file
    //     $zip = new \ZipArchive();
    //     $zip->open(storage_path('app/' . $path));
    //     $extractPath = storage_path('app/temp/extracted');
    //     $zip->extractTo($extractPath);
    //     $zip->close();

    //     // Initialize variables to store success or failure of insertions
    //     $insertSuccess = true;

    //     // Arrays to hold data for bulk insert
    //     $f11Data = [];
    //     $localAreaListingsData = [];

    //     // Loop through the extracted files
    //     foreach (glob($extractPath . '/*.csv') as $csvFile) {
    //         $fileName = basename($csvFile);

    //         // Read the CSV file data
    //         $csvData = array_map('str_getcsv', file($csvFile));

    //         // Skip the header row
    //         $header = array_shift($csvData);

    //         // Insert the data into the corresponding table
    //         foreach ($csvData as $row) {
    //             // Ensure the row matches the header in length by padding with nulls
    //             $row = array_pad($row, count($header), null);

    //             $data = array_combine($header, $row);

    //             // Separate data for F11Model and LocalAreaListingsModel
    //             if (str_starts_with($fileName, 'f11')) {
    //                 $f11Data[] = $data;
    //             } elseif (str_starts_with($fileName, 'localarea_listings')) {
    //                 $localAreaListingsData[] = $data;
    //             }
    //         }
    //     }

    //     // Perform bulk insert for F11Model
    //     try {
    //         F11Model::insert($f11Data);
    //     } catch (\Exception $e) {
    //         $insertSuccess = false;
    //         dd('Insert failed for F11Model', $e->getMessage());
    //     }

    //     // Perform bulk insert for LocalAreaListingsModel
    //     try {
    //         LocalAreaListingsModel::insert($localAreaListingsData);
    //     } catch (\Exception $e) {
    //         $insertSuccess = false;
    //         dd('Insert failed for LocalAreaListingsModel', $e->getMessage());
    //     }

    //     // Clean up the temporary files
    //     Storage::delete($path);
    //     File::deleteDirectory($extractPath);

    //     // Dump the result of the insertion
    //     if ($insertSuccess) {
    //         dd('Insert successful');
    //     } else {
    //         dd('Insert failed');
    //     }
    // }
    public function render()
    {
        return view('livewire.consolidation-component.consolidation');
    }
}
