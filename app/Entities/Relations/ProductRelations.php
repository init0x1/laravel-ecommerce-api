<?php
namespace App\Entities\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


use App\Entities\Models\Category;
use App\Entities\Models\User;
use App\Entities\Models\Stock;
use App\Entities\Models\OrderItem;

trait ProductRelations
{

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

       public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}
