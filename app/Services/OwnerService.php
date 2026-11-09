<?php

namespace App\Services;

use App\Contract\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OwnerService
{
    public function paginate(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->where('role', Role::$OWNER)
            ->with('shop:id,owner_id,name,slug')
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
                'role' => Role::$OWNER,
            ]);

            $this->assignShop($user, $data['shop_id'] ?? null);

            return $user->load('shop:id,owner_id,name,slug');
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

            return $user->load('shop:id,owner_id,name,slug');
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

        Store::where('owner_id', $user->id)
            ->when($shopId, fn ($query) => $query->where('id', '!=', $shopId))
            ->update(['owner_id' => null]);

        if ($shopId) {
            Store::whereKey($shopId)->update(['owner_id' => $user->id]);
        }
    }
}
