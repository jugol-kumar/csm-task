<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ImageService::url($value),
        );
    }

    public function listings()
    {
        return $this->hasMany(StoreProductMapping::class, 'product_id');
    }
}
