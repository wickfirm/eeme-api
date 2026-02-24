<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentPackageRequest
 *
 * @property int $id
 * @property int $talent_package_id last active package
 * @property string $order_ref
 * @property string|null $agreement_id
 * @property int $type 1 for 3 months | 2 for 1 year | 3 for other (no time limit)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Talent\TalentPackage $talent_package
 * @property-read \App\Models\Talent\TalentPackageResponse|null $talent_package_response
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereOrderRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereTalentPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $expired_at Subscription time limit
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest whereExpiredAt($value)
 * @property int|null $payment_method
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageRequest wherePaymentMethod($value)
 */
class TalentPackageRequest extends Model
{
    protected $table = 'talent_package_requests';
    protected $fillable = ['talent_package_id', 'order_ref', 'agreement_id', 'payment_method', 'type', 'expired_at'];

    public function talent_package()
    {
        return $this->belongsTo(TalentPackage::class, 'talent_package_id', 'id');
    }

    public function talent_package_response()
    {
        return $this->hasOne(TalentPackageResponse::class, 'talent_package_request_id', 'id');
    }
}
