<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, BaseModel;

    public $fillable = [
        'name',
        'description',
        'status',
        'category_id',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
        ];
    }

    protected $appends = ['display_status'];

    public function getDisplayStatusAttribute()
    {
        return $this->status->name();
    }

    protected $relationship = [
        'category' => [
            'model' => Category::class
        ],
        'tags' => [
            'model' => Tag::class
        ],
        'variants' => [
            'model' => ProductVariant::class
        ],
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    protected $scopedFilters = [
        'category_ids',
        'tag_ids',
        'variant_ids',
    ];

    public function scopeCategoryIds(Builder $query, $ids)
    {
        return $query->whereIn('category_id', $ids);
    }

    public function scopeTagIds(Builder $query, $ids)
    {
        return $query->whereHas('tags', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        });
    }

    public function scopeVariantIds(Builder $query, $ids)
    {
        return $query->whereHas('variants', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        });
    }
}
