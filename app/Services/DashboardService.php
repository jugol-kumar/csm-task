<?php

namespace App\Services;

use App\Contract\Role;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProductMapping;
use App\Models\User;

class DashboardService
{
    public function admin(): array
    {
        return [
            'shops' => Store::count(),
            'owners' => User::where('role', Role::$OWNER)->count(),
            'products' => Product::count(),
            'listings' => StoreProductMapping::count(),
            'customers' => User::where('role', Role::$CUSTOMER)->count(),
        ];
    }

    public function owner(User $user, Store $shop): array
    {
        return [
            'listings' => StoreProductMapping::where('store_id', $shop->id)->count(),
            'active' => StoreProductMapping::where('store_id', $shop->id)->count(),
            'catalog' => Product::count(),
            'available' => Product::whereDoesntHave('listings', fn ($query) => $query->where('store_id', $shop->id))->count(),
            'mine' => Product::where('user_id', $user->id)->count(),
        ];
    }
}
