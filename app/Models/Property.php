<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'property_type',
        'address',
        'city',
        'province_state',
        'country',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'latitude',
        'longitude',
        'image_path',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area_sqft' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];
}
