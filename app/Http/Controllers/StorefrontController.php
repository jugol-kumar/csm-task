<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Models\StoreProductMapping;
use App\Services\StorefrontService;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function __construct(private readonly StorefrontService $store)
    {
    }

    public function shops(Request $request)
    {
        return response()->json($this->store->shops($request->query('search', '')));
    }

    public function createStore(StoreRequest $request)
    {
        return response()->json($this->store->createStore($request->validated()));
    }

    public function shop(Store $store)
    {
        return response()->json($this->store->store($store));
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

    public function product(Store $store, StoreProductMapping $listing)
    {
        $visible = $this->store->listing($store, $listing);
        abort_if($visible === null, 404);
        return response()->json($visible);
    }
}
