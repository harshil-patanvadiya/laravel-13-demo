<?php

namespace App\Services;

use App\Enums\CategoryStatus;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\PaginationTrait;

class CategoryService
{
    use PaginationTrait;

    public function __construct(public Category $category) {}

    public function store(array $inputs): array
    {
        $category = $this->category->create([
            'name' => $inputs['name'],
            'status' => $inputs['status'] ?? CategoryStatus::Active,
        ]);

        return [
            'message' => 'Category created successfully.',
            'data' => new CategoryResource($category),
        ];
    }

    public function collection($inputs)
    {
        $categories = $this->category->getQB();

        return $this->paginationAttribute($categories);
    }
}
