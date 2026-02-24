<?php

namespace App\Models\Order;

use App\Models\Addons\Addon;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table = 'promo_codes';

    // has many orders
    public function order_request()
    {
        return $this->hasMany(OrderRequest::class);
    }

    public function addons(){
        return $this->belongsToMany(Addon::class, 'promo_codes_addons');
    }
}
