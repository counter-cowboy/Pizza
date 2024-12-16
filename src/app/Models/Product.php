<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $with = ['category'];
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
    ];


    /**
     * @return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Cart>
     */
    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)->withPivot('created_at');
    }

    /**
     * @return BelongsToMany<Order>
     */
    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity');
    }
}
