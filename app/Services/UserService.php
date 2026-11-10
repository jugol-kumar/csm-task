<?php

namespace App\Services;

use App\Contract\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

readonly class UserService
{
    public function __construct(private StoreService $storeService)
    {
    }

    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->with('store:id,user_id,name,slug')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'] ?? Role::$OWNER,
            ]);

            $storeId = $data['shop_id'] ?? null;
            if(empty($storeId)){
                $data = [
                    'name' => $data['new_store']['name'],
                    'description' => $data['new_store']['description'],
                ];
                $store = $this->storeService->createStore($data, $user);
                $storeId = $store->id;
            }

            $this->assignShop($user, $storeId?? null);

            return $user->load('store:id,user_id,name,slug');
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();
            $this->assignShop($user, $data['shop_id'] ?? null);
            return $user->load('store:id,user_id,name,slug');
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->delete();
        });
    }

    private function assignShop(User $user, $shopId): void
    {
        $shopId = $shopId ? (int) $shopId : null;

        Store::where('user_id', $user->id)
            ->when($shopId, fn ($query) => $query->where('id', '!=', $shopId))
            ->update(['user_id' => null]);

        if ($shopId) {
            Store::whereKey($shopId)->update(['user_id' => $user->id]);
        }
    }
}
