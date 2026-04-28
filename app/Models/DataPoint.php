<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPoint extends Model
{
    protected $fillable = [
        'indicator_id',
        'region_id',
        'year',
        'value',
        'source',
        'status'
    ];

    protected $casts = [
        'year' => 'integer',
        'value' => 'decimal:4'
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}