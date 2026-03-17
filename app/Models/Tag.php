<?php

namespace App\Models;

use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use BaseModel;

    protected $fillable = [
        'name',
    ];
}
