<?php

namespace App\Helpers\Order;

use App\Models\Addons\Addon;
use App\Models\Order\OrderRequest;
use App\Models\Order\OrderResponse;

class OrderHelper {
    public static function createOrderResponse( $order_request , $status ) {
        $order_response                   = new OrderResponse();
        $order_response->order_request_id = $order_request->id;
        $order_response->status           = $status;
        $order_response->response         = json_encode( [ '' ] );
        $order_response->save();
        return $order_response;
    }
    public static function addonsAppliedToOrder( OrderRequest $order_request ) {

        $matches = str_replace( "\"" , '' , $order_request->addons_ids );
        $matches = explode( "," , $matches );
        $addons  = collect();
        foreach ( $matches as $value ) {
            $n      = str_replace( '"' , '' , $value );
            $addon  = Addon::where( 'id' , $n )->get();
            $addons = $addons->merge( $addon );
        }
        return $addons;
    }
}
