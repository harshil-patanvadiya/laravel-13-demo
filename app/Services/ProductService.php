<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\PaginationTrait;
use App\Enums\ProductStatus;

class ProductService
{
    use PaginationTrait;
    public function __construct(public Product $product) {}

    public function store(array $data): array
    {
        $product = $this->product->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => $data['status'] ?? ProductStatus::Active,
            'category_id' => $data['category_id'],
        ]);

        if (! empty($data['tag_ids'])) {
            $product->tags()->sync($data['tag_ids']);
        }

        if (! empty($data['variants']) && is_array($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                $product->variants()->create([
                    'size' => $variant['size'] ?? null,
                    'color' => $variant['color'] ?? null,
                    'price' => $variant['price'] ?? null,
                ]);
            }
        }

        return [
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product->load('category', 'tags', 'variants')),
        ];
    }

    public function collection(array $inputs)
    {
        $query = $this->product->getQB();

        return $this->paginationAttribute($query);
    }
}
