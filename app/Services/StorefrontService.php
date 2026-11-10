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
            ->withCount(['listings'])
            ->when($search, fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($perPage);
    }
    public function createStore(array $data): Store
    {
        return Store::create($data);
    }

    public function store(Store $store): Store
    {
        return $store->loadCount(['listings']);
    }

    public function products(Store $store, ?string $search, ?string $sort, int $perPage = 12): LengthAwarePaginator
    {
        return $this->listings($store->id, $search, $sort, $perPage);
    }

    public function latest(?string $search, ?string $sort, int $perPage = 12): LengthAwarePaginator
    {
        return $this->listings(null, $search, $sort, $perPage);
    }

    public function listing(Store $store, StoreProductMapping $listing): ?StoreProductMapping
    {
        $visible = StoreProductMapping::query()
            ->where('store_product_mappings.store_id', $store->id)
            ->whereKey($listing->id)
            ->with(['product', 'store:id,name,slug,user_id', 'store.owner:id,name,email'])
            ->first();

        return $visible;
    }

    private function listings(?int $shopId, ?string $search, ?string $sort, int $perPage): LengthAwarePaginator
    {
        return StoreProductMapping::query()
            ->with(['product', 'store:id,name,slug'])
            ->when($shopId, fn (Builder $query) => $query->where('store_product_mappings.store_id', $shopId))
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
