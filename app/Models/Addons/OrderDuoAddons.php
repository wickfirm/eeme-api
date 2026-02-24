<?php

namespace App\Models\Addons;

use Illuminate\Database\Eloquent\Model;

use App\Models\Order\OrderRequest;
use App\Models\Addons\Addon;
use App\Models\Talent\Talent;

class OrderDuoAddons extends Model
{
    protected $guarded = [] ;

    protected $table = 'order_duo_addons';

    public function orders_requests(){
        return $this->belongsTo(OrderRequest::class, 'order_request_id');
    }

    public function addons(){
        return $this->belongsTo(Addons::class, 'addons_id');
    }

    public function talents(){
        return $this->belongsTo(Talent::class, 'talent_duo_id');
    }

    
}
