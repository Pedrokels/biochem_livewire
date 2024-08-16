<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalAreaListingsModel extends Model
{
    use HasFactory;


    protected $table = 'localarea_listings_conso';

    protected $guarded = [];
}
