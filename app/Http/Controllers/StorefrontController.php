<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreProductMapping;
use App\Services\StorefrontService;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function __construct(private StorefrontService $store)
    {
    }

    public function shops(Request $request)
    {
        return response()->json($this->store->shops($request->query('search', '')));
    }

    public function shop(Store $shop)
    {
        return response()->json($this->store->shop($shop));
    }

    public function products(Request $request, Store $store)
    {
        $data = $this->store->products($store,
            (string)$request->query('search', ''),
            (string)$request->query('sort', '')
        );
        return response()->json($data);
    }

    public function latest(Request $request)
    {
        return response()->json(
            $this->store->latest(
                (string)$request->query('search', ''),
                (string)$request->query('sort', '')
            )
        );
    }

    public function product(Store $shop, StoreProductMapping $listing)
    {
        $visible = $this->store->listing($shop, $listing);

        abort_if($visible === null, 404);

        return response()->json($visible);
    }
}
