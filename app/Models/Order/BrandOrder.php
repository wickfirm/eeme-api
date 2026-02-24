<?php

namespace App\Models\Order;

use App\Models\Brand\Brand;
use App\Models\Talent\Talent;
use Illuminate\Database\Eloquent\Model;

class BrandOrder extends Model
{
    protected $table = 'brands_orders';

    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function brand() {
        return $this->belongsTo( Brand::class );
    }
}
