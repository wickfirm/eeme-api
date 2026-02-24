<?php

namespace App\Models\Order;

use App\Models\Misc\Occasion;
use App\Models\Talent\Talent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\order\OrderRequest
 * @property int                             $id
 * @property int                             $talent_id
 * @property int                             $type 1 = For Other, 2 = For Self
 * @property string|null                     $order_ref
 * @property string                          $sender
 * @property string|null                     $recipient
 * @property int                             $occasion_id
 * @property string                          $payment
 * @property string                          $message
 * @property float                           $price
 * @property string                          $email
 * @property int                             $is_public
 * @property float|null                      $total_addons_price
 * @property string|null                     $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read OrderResponse              $order_response
 * @property-read \App\Models\Talent\Talent  $talent
 * @property string|null                     $payment_method
 * @property string|null                     $notes
 * @property-read \App\Models\Misc\Occasion  $occasion
 * @property int                             $is_archived
 * @property int|null                        $promo_code_id
 * @property string|null                     $cancelled_order_reason
 * @property float|null                      $price_package
 * @property string|null                     $phone_number
 * @property int                             $order_type
 * @property string|null                     $code
 * @property int                             $admin_notified
 * @property int                             $talent_notified
 * @property int                             $client_notified
 * @property string|null                     $addons_ids
 * @property float|null                      $talent_duo_price_first
 * @property float|null                      $talent_duo_price_second
 * @property-read PromoCode|null             $promo_code
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereAddonsIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereAdminNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereCancelledOrderReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereClientNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereOrderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest wherePricePackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest wherePromoCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereTalentDuoPriceFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereTalentDuoPriceSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereTalentNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereTotalAddonsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereEmail( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereIsPublic( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereMessage( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereOccasionId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereOrderRef( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest wherePrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereRecipient( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereSender( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereTalentId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereType( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRequest whereIsArchived($value)
 */
class OrderRequest extends Model {
    protected $table = 'orders_requests';

    protected $guarder = [];

    public function occasion() {
        return $this->belongsTo( Occasion::class );
    }

    public function talent() {
        return $this->belongsTo( Talent::class );
    }

    public function order_response() {
        return $this->hasOne( OrderResponse::class );
    }
    public function promo_code()
    {
        return $this->belongsTo(PromoCode::class);
    }

    protected static function booted () {
        static::addGlobalScope ('order_request', function (Builder $builder) {
            $builder->with (['occasion']);
        });
    }
}
