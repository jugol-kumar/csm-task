<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreProductMapping;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class StorefrontService
{
    public function shops(?string $search, int $perPage = 12): LengthAwarePaginator
    {
        return Store::query()
//            ->whereHas('listings', fn (Builder $query) => $query->where('is_active', true))
//            ->withCount(['listings' => fn (Builder $query) => $query->where('is_active', true)])
            ->when($search, fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function shop(Store $shop): Store
    {
        return $shop->loadCount(['listings' => fn (Builder $query) => $query->where('is_active', true)]);
    }

    public function products(Store $shop, ?string $search, ?string $sort, int $perPage = 12): LengthAwarePaginator
    {
        return $this->listings($shop->id, $search, $sort, $perPage);
    }

    public function latest(?string $search, ?string $sort, int $perPage = 12): LengthAwarePaginator
    {
        return $this->listings(null, $search, $sort, $perPage);
    }

    public function listing(Store $shop, StoreProductMapping $listing): ?StoreProductMapping
    {
        $visible = StoreProductMapping::query()
            ->visible()
            ->where('shop_id', $shop->id)
            ->whereKey($listing->id)
            ->with(['product', 'shop:id,name,slug,address,phone'])
            ->first();

        return $visible;
    }

    private function listings(?int $shopId, ?string $search, ?string $sort, int $perPage): LengthAwarePaginator
    {
        return StoreProductMapping::query()
            ->visible()
            ->with(['product', 'shop:id,name,slug'])
            ->when($shopId, fn (Builder $query) => $query->where('shop_id', $shopId))
            ->when($search, function (Builder $query) use ($search) {
                $query->where(function (Builder $inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhereHas('product', fn (Builder $product) => $product->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($sort === 'price_asc', fn (Builder $query) => $query->orderBy('price'))
            ->when($sort === 'price_desc', fn (Builder $query) => $query->orderByDesc('price'))
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}
