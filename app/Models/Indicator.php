<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $fillable = [
        'code',
        'name_en', 
        'unit',
        'category'
    ];

    public function dataPoints()
    {
        return $this->hasMany(DataPoint::class);
    }
}