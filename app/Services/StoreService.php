<?php

namespace App\Services;

use App\Contract\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreService
{
    public function __construct(private ImageService $images)
    {
    }

    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Store::query()
            ->with('owner:id,name,email')
             ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $data, User $creator): Store
    {
        $shop = Store::create(array_merge(
             $data,
            ['user_id' => $creator->id],
            ['role' => Role::$OWNER],
            ['slug' => $this->uniqueSlug($data['name'])]
        ));

        return $shop->load('owner:id,name,email');
    }

    public function update(Store $shop, array $data): Store
    {
        if ($shop->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $shop->id);
        }

        $shop->update($data);

        return $shop->refresh()->load('owner:id,name,email')->loadCount('listings');
    }

    public function delete(Store $shop): void
    {
        DB::transaction(function () use ($shop) {
            $shop->listings()
                ->whereNotNull('thumbnail')
                ->pluck('thumbnail')
                ->each(fn ($path) => $this->images->delete($path));

            $shop->delete();
        });
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'shop';
        $slug = $base;
        $counter = 2;

        while (
            Store::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
