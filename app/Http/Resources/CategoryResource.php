<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    use ResourceFilterable;

    protected $model = Category::class;

    public function toArray(Request $request): array
    {
        $data = $this->fields();

        return $data;
    }
}
