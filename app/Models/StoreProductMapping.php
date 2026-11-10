<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreProductMapping extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'assign_by',
        'name',
        'description',
        'price',
        'image',
        'assign_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}
