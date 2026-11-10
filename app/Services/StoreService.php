<?php

namespace App\Services;

use App\Contract\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreService
{
    public function __construct(private readonly ImageService $images, private AuthService $authService)
    {
    }

    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Store::query()
            ->with('owner:id,name,email')
            ->withCount('listings')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $data): Store|bool
    {
        $user = $this->authService->register([
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' => $data['owner_password'],
            'role' => Role::$OWNER,
        ]);
        if($user){
            return $this->createStore($data, $user);
        }
        return false;
    }

    public function createStore(array $data, User $user)
    {
        $shop = Store::create(array_merge(
            $data,
            ['user_id' => $user->id],
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
