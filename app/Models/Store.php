<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner'
    ];

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ImageService::url($value),
        );
    }
    protected function banner(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ImageService::url($value),
        );
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function listings()
    {
        return $this->hasMany(StoreProductMapping::class, 'store_id');
    }
}
