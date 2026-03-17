<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    use ResourceFilterable;

    protected $model = ProductVariant::class;

    public function toArray(Request $request): array
    {
        return $this->fields();
    }
}
