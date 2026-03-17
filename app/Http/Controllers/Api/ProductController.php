<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponser;

    public function __construct(private ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->collection($request->all());

        return $this->success(ProductResource::collection($products));
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = $this->productService->store($request->validated());

        return $this->success($product, 201);
    }
}

