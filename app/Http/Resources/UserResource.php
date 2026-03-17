<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Traits\ResourceFilterable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ResourceFilterable;

    protected $model = User::class;

    public function toArray(Request $request): array
    {
        $data = $this->fields();

        $data['roles'] = $this->getRoleNames();
        return $data;
    }
}
