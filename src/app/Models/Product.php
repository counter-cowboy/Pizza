<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */

    protected $table = 'products';
    protected $with = 'category';
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)->withPivot('created_at');
    }

    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity');
    }

    public function scopeWhereCategoryId($query, $catId)
    {
        return $query->where('category_id', $catId);
    }
}
