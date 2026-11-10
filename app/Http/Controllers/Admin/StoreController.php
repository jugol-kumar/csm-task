<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    public function __construct(private readonly StoreService $store)
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->store->paginate((string)$request->query('search', '')));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $shop = $this->store->create($request->validated());
        return response()->json($shop);
    }

    public function show(Store $shop)
    {
        return response()->json($shop->load('owner:id,name,email')->loadCount('listings'));
    }

    public function update(StoreRequest $request, Store $store)
    {
        return response()->json($this->store->update($store, $request->validated()));
    }

    public function destroy(Store $shop): Response
    {
        $this->store->delete($shop);
        return response()->noContent();
    }
}
