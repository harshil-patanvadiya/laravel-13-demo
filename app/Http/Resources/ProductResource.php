<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    use ResourceFilterable;

    protected $model = Product::class;

    public function toArray(Request $request): array
    {
        $data = $this->fields();

        $data['category'] = new CategoryResource($this->whenLoaded('category'));
        $data['tags'] = TagResource::collection($this->whenLoaded('tags'));
        $data['variants'] = ProductVariantResource::collection($this->whenLoaded('variants'));

        return $data;
    }
}
