<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function listings()
    {
        return $this->hasMany(StoreProductMapping::class, 'store_id');
    }
}
