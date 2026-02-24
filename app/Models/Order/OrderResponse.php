<?php

namespace App\Models\Order;

use App\Models\Order\OrderRequest;
use App\Models\Talent\TalentOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderResponse
 *
 * @property int                                 $id
 * @property int                                 $order_request_id
 * @property int                                 $status
 * @property mixed|null                          $response The transaction response
 * @property \Illuminate\Support\Carbon|null     $created_at
 * @property \Illuminate\Support\Carbon|null     $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse whereOrderRequestId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse whereResponse( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse whereStatus( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderResponse whereUpdatedAt( $value )
 * @mixin \Eloquent
 * @property-read \App\Models\Order\OrderRequest $request
 * @property-read \App\Models\Order\OrderRequest $order_request
 */
class OrderResponse extends Model {
    protected $table = 'orders_responses';

    protected $dates = [ 'created_at', 'updated_at' ];


    public function order_request() {
        return $this->belongsTo( OrderRequest::class );
    }

    public function talent_order() {
        return $this->hasOne( TalentOrder::class );
    }

    protected static function booted () {
        static::addGlobalScope ('order_response', function (Builder $builder) {
            $builder->with (['talent_order.talent_videos']);
        });
    }
}
