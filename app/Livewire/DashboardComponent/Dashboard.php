<?php

namespace App\Livewire\DashboardComponent;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard-component.dashboard');
    }
}
