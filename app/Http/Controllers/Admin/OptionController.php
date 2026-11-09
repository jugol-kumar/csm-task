<?php

namespace App\Http\Controllers\Admin;

use App\Contract\Role;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class OptionController extends Controller
{
    public function owners(): JsonResponse
    {
        $owners = User::query()
            ->where('role', Role::$OWNER)
            ->with('shop:id,owner_id')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json([
            'data' => $owners->map(fn (User $owner) => [
                'id' => $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
                'shop_id' => $owner->shop?->id,
            ])->values(),
        ]);
    }

    public function stores(): JsonResponse
    {
        return response()->json([
            'data' => Store::query()->orderBy('name')->get(['id', 'name', 'owner_id']),
        ]);
    }
}
