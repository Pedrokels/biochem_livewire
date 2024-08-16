<?php

use App\Livewire\ConsolidationComponent\Consolidation;
use App\Livewire\DashboardComponent\Dashboard;

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/consolidation', Consolidation::class)->name('consolidation');
