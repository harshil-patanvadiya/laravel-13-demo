<?php

namespace App\Http\Resources;

use App\Models\Tag;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    use ResourceFilterable;

    protected $model = Tag::class;

    public function toArray(Request $request): array
    {
        return $this->fields();
    }
}

