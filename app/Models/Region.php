<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'country_code',
        'name',
        'level',
        'parent_id'
    ];

    // 子地区（sub-national）
    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    // 父地区
    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function dataPoints()
    {
        return $this->hasMany(DataPoint::class);
    }
}