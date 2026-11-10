<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $products)
    {
    }

    public function index(Request $request)
    {
            $data = $this->products->paginate($request->query('search', ''), $request->query('source', ''), user:$request->user());
            return response()->json($data);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        return response()->json($this->products->create($request->validated(), $request->user()));
    }

    public function show(Product $product)
    {
        return response()->json($product->load('creator:id,name')->loadCount('listings'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        return response()->json($this->products->update($product, $request->validated()));
    }

    public function destroy(Product $product): Response
    {
        $this->products->delete($product);

        return response()->noContent();
    }
}
