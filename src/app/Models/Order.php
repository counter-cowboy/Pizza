<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_amount',
        'user_id',
        'status',
        'address',
        'phone',
        'email',
        'delivery_time',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return Collection|Product[]
     */
    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
