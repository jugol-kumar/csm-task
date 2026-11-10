<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProductMapping;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

readonly class ListingService
{
    public function __construct(private ImageService $images)
    {
    }

    public function forShop(Store $store, ?string $search, int $perPage = 12): LengthAwarePaginator
    {
        return StoreProductMapping::query()
            ->where('store_id', $store->id)
            ->with(['product', 'store:id,name,slug'])
            ->when($search, function (Builder $query) use ($search) {
                $query->where(function (Builder $inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhereHas('product', fn (Builder $product) => $product->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('id')
            ->paginate($perPage);
    }

    public function catalog(Store $store, int $userId, ?string $search, string $scope, int $perPage = 12): LengthAwarePaginator
    {
        $inStore = fn ($listing) => $listing->where('store_id', $store->id);

        return Product::query()
            ->with([
                'creator:id,name',
                'listings' => $inStore,
            ])
            ->when($search, function (Builder $query) use ($search, $store) {
                $query->where(function (Builder $inner) use ($search, $store) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhereHas('listings', fn ($listing) => $listing
                            ->where('store_id', $store->id)
                            ->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($scope === 'available', fn (Builder $query) => $query->whereDoesntHave('listings', $inStore))
            ->when($scope === 'mine', fn (Builder $query) => $query->where('user_id', $userId))
            ->when(! in_array($scope, ['available', 'mine'], true), fn (Builder $query) => $query->whereHas('listings', $inStore))
            ->latest('id')
            ->paginate($perPage)
            ->through(function (Product $product) use ($userId) {
                $listing = $product->listings->first();
                return [
                    'id' => $listing?->id,
                    'product_id' => $product->id,
                    'name' => $listing?->name ?? $product->name,
                    'description' => $listing?->description ?? $product->description,
                    'price' => $listing?->price ?? $product->price,
                    'image' => $listing?->image ?? $product->image,
                    'is_listed' => $listing !== null,
                    'is_mine' => (int) $product->user_id === $userId,
                    'creator' => $product->creator,
                ];
            });
    }
    public function attach(Store $store, Product $product, array $data, int $userId): StoreProductMapping
    {
        $exists = StoreProductMapping::query()
            ->where('store_id', $store->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'product' => 'This product is already in your shop.',
            ]);
        }

        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        $listing = StoreProductMapping::create([
            'store_id' => $store->id,
            'product_id' => $product->id,
            'assign_by' => $userId,
            'name' => $title !== '' ? $title : $product->name,
            'description' => $description !== '' ? $description : $product->description,
            'price' => $data['price'],
            'image' => isset($data['thumbnail'])
                ? $this->images->store($data['thumbnail'], 'listings')
                : null,
            'assign_date' => now()->toDateString(),
        ]);

        return $listing->load(['product', 'store:id,name,slug']);
    }

    public function update(StoreProductMapping $listing, array $data): StoreProductMapping
    {
        $listing->loadMissing('product');

        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        $listing->update([
            'name' => $title !== '' ? $title : $listing->product->name,
            'description' => $description !== '' ? $description : $listing->product->description,
            'price' => $data['price'],
            'image' => $this->images->replace(
                $listing->image,
                $data['thumbnail'] ?? null,
                (bool) ($data['remove_thumbnail'] ?? false),
                'listings'
            ),
        ]);

        return $listing->refresh()->load(['product', 'store:id,name,slug']);
    }

    public function detach(StoreProductMapping $listing): void
    {
        $this->images->delete($listing->image);
        $listing->delete();
    }
}
