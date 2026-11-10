<?php

namespace App\Http\Controllers\Owner;

use App\Http\Requests\ListingRequest;
use App\Models\Product;
use App\Models\StoreProductMapping;
use App\Services\ListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListingController extends OwnerBaseController
{
    public function __construct(private readonly ListingService $listings)
    {
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->listings->forShop($this->shop($request), (string) $request->query('search', ''))
        );
    }

    public function store(ListingRequest $request): JsonResponse
    {
        $shop = $this->shop($request);
        $product = Product::findOrFail($request->validated('product_id'));

        $listing = $this->listings->attach($shop, $product, $request->validated(), $request->user()->id);
        return response()->json($listing);
    }

    public function update(ListingRequest $request, StoreProductMapping $listing)
    {
        $this->ownListing($request, $listing);
        return response()->json($this->listings->update($listing, $request->validated()));
    }

    public function destroy(Request $request, StoreProductMapping $listing): Response
    {
        $this->listings->detach($listing);
        return response()->noContent();
    }

    private function ownListing(Request $request, StoreProductMapping $listing): void
    {
        abort_unless($listing->shop_id === $this->shop($request)->id, 404);
    }
}
