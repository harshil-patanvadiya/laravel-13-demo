<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponser;

    public function __construct(private CategoryService $categoryService) {}

    public function index(Request $request): JsonResponse
    {
        $categories = $this->categoryService->collection($request->all());

        return $this->success(CategoryResource::collection($categories));
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = $this->categoryService->store($request->validated());

        return $this->success($category);
    }
}
