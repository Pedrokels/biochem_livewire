<?php

namespace App\Livewire\ConsolidationComponent\InsertedConsolidationComponentSub;

use App\Models\F11Model;
use App\Models\LocalAreaListingsModel;
use App\Models\LocalSurveyAreas;

use Livewire\Attributes\On;
use Livewire\Component;

class ConsolidationSuccess extends Component
{

    #[On('reload-delete')]
    public function deleteAll()
    {
        // Delete all records from the F11Model table
        F11Model::query()->delete();

        // Delete all records from the LocalAreaListingsModel table
        LocalAreaListingsModel::query()->delete();
    }


    #[On('success-consolidation')]
    public function updateSuccessConso($successConso = null)
    {
        $this->dispatch('success-consolidation-open-modal');
    }

    public function render()
    {
        $ConsolidatedData = LocalAreaListingsModel::select('localarea_listings_conso.*', 'localsurveyareas.areaname')
            ->join('localsurveyareas', 'localarea_listings_conso.eacode', '=', 'localsurveyareas.eacode')
            ->get();

        return view('livewire.consolidation-component.inserted-consolidation-component-sub.consolidation-success', [
            "ConsolidatedData" => $ConsolidatedData,
        ]);
    }
}
