<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OwnerCatalogService
{
    public function __construct(
        private ProductService $products,
        private ListingService $listings
    ) {
    }

    public function createAndList(User $user, Store $store, array $data)
    {
        return DB::transaction(function () use ($user, $store, $data) {
            $product = $this->products->create($data, $user);
            return $this->listings->attach($store, $product, ['price' => $data['base_price']], $user->id);
        });
    }

    public function deleteOwn(Store $store, Product $product): void
    {
        $usedElsewhere = $product->listings()->where('shop_id', '!=', $store->id)->exists();

        if ($usedElsewhere) {
            throw ValidationException::withMessages([
                'product' => ['Other shops already list this product, so it cannot be deleted.'],
            ]);
        }

        $this->products->delete($product);
    }
}
