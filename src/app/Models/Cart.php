<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';

    protected $fillable = [
        'user_id',
    ];
    protected $with = ['product', 'user'];

    /**
     * @return BelongsTo<User, Cart>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Product>
     */
    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
