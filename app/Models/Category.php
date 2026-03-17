<?php

namespace App\Models;

use App\Enums\CategoryStatus;
use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => CategoryStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected $appends = ['display_status'];

    public function getDisplayStatusAttribute()
    {
        return $this->status?->name();
    }
}
