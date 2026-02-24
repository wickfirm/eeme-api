<?php


namespace App\Helpers\PromoCode;


use App\Models\Order\OrderRequest;
use App\Models\Talent\TalentAddons;
use Carbon\Carbon;

class PromoCodeHelper {
    public static function getDiscountedPrice( OrderRequest $order_request ) {
        $promo_code = $order_request->promo_code;

        $total = ( $order_request->price != null ? $order_request->price : 0 ) + ( $order_request->total_addons_price != null ? $order_request->total_addons_price : 0 ) + ( $order_request->talent_duo_price_first != null ? $order_request->talent_duo_price_first : 0 ) + ( $order_request->talent_duo_price_second != null ? $order_request->talent_duo_price_second : 0 );
        if ( $promo_code ) {
            if (
                $promo_code->is_active == 1 && Carbon::now()->gte( $promo_code->start_date ) && Carbon::now()->lte( $promo_code->expiry_date )
            ) {
                if (
                    $promo_code->talent_id != null && $promo_code->count > 0 && $promo_code->talent_id == $order_request->talent_id || $promo_code->talent_id != null && $promo_code->count == - 1 && $promo_code->talent_id == $order_request->talent_id || $promo_code->talent_id == null && $promo_code->count > 0 || $promo_code->talent_id == null && $promo_code->count == - 1
                ) {
                    // if promo_code has talent restrictions + promo_code count > 0 + promo_code->talent_id = order_request->talent_id
                    // if promo_code has talent restrictions + promo_code count == -1 (unlimited) + promo_code->talent_id = order_request->talent_id
                    // if promo_code does NOT have talent restrictions + promo_code count > 0
                    // if promo_code does NOT have talent restrictions + promo_code count == -1 (unlimited)
                    $total = self::setPrice( $order_request );
                }
            }
        }
        return $total;
    }

    private static function setPrice( OrderRequest $order_request ) {

        $promo_code = $order_request->promo_code;

        $base_price = ( $order_request->price != null ? $order_request->price : 0 );

        $addons_included_discount = 0;
        $addons_excluded_discount = 0;

        $addons_ids = str_replace( '"' , '' , $order_request->addons_ids );
        $addons_ids = array_map( 'intval' , explode( ',' , $addons_ids ) );

        foreach ( $addons_ids as $ai ) {
            $talent_addon = TalentAddons::where( 'talent_id' , $order_request->talent_id )->where( 'addons_id' , $ai )->first();
            if ( $talent_addon ) {
                if ( $promo_code->addons()->find( $ai ) ) {
                    // if promo code includes this specific add-on
                    $addons_included_discount += $talent_addon->price;
                }
                else {
                    // if promo code does not include this specific add-on
                    $addons_excluded_discount += $talent_addon->price;
                }
            }
        }

        $total = $base_price + $addons_included_discount + $addons_excluded_discount;

        if ( $promo_code->promo_type == 1 ) {
            // promo code ONLY applies to base price

            if ( $promo_code->type == 0 ) {
                // discount in $
                $base_price = $base_price - $promo_code->number;
                $base_price = $base_price < 0 ? 0 : $base_price;
                $total      = $base_price + $addons_included_discount + $addons_excluded_discount;
            }
            elseif ( $promo_code->type == 1 ) {
                // discount in %
                $base_price = $base_price * ( 100 - $promo_code->number ) / 100;
                $base_price = $base_price < 0 ? 0 : $base_price;
                $total      = $base_price + $addons_included_discount + $addons_excluded_discount;
            }
        }
        elseif ( $promo_code->promo_type == 2 ) {
            // promo code ONLY applies to add-ons
            if ( $promo_code->type == 0 ) {
                // discount in $
                $addons_included_discount = $addons_included_discount - $promo_code->number;
                $addons_included_discount = $addons_included_discount < 0 ? 0 : $addons_included_discount;
                $total                    = $base_price + $addons_included_discount + $addons_excluded_discount;
            }
            elseif ( $promo_code->type == 1 ) {
                // discount in %
                $addons_included_discount = $addons_included_discount * ( 100 - $promo_code->number ) / 100;
                $addons_included_discount = $addons_included_discount < 0 ? 0 : $addons_included_discount;
                $total                    = $base_price + $addons_included_discount + $addons_excluded_discount;
            }
        }
        elseif ( $promo_code->promo_type == 3 ) {
            // promo code applies to BOTH base price and add-ons
            if ( $promo_code->type == 0 ) {
                // discount in $
                $discount = $base_price + $addons_included_discount - $promo_code->number;
                $discount = $discount < 0 ? 0 : $discount;
                $total    = $discount + $addons_excluded_discount;
            }
            elseif ( $promo_code->type == 1 ) {
                // discount in %
                $discount = ( $base_price + $addons_included_discount ) * ( 100 - $promo_code->number ) / 100;
                $discount = $discount < 0 ? 0 : $discount;
                $total    = $discount + $addons_excluded_discount;
            }
        }

        return $total;
    }

    public static function promoCount( OrderRequest $order_request ) {
        if ( $order_request->promo_code ) {
            if ( $order_request->talent_id == $order_request->promo_code->talent_id || $order_request->promo_code->talent_id == null ) {
                if ( $order_request->promo_code->count > 0 ) {
                    $order_request->promo_code->count = $order_request->promo_code->count - 1;
                    $order_request->promo_code->save();
                }
            }
        }
    }


}

