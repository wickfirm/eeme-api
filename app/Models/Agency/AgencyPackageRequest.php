<?php

namespace App\Models\Agency;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Agency\AgencyPackageRequest
 *
 * @property int $id
 * @property int $agency_package_id
 * @property string $order_ref
 * @property string $agreement_id
 * @property int $type
 * @property string $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency\AgencyPackage $agency_package
 * @property-read \App\Models\Agency\AgencyPackageResponse|null $agency_package_response
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereAgencyPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereOrderRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AgencyPackageRequest extends Model
{
    protected $table = 'agency_package_requests';
    protected $fillable = ['agency_package_id', 'order_ref', 'agreement_id', 'type', 'expired_at'];

    public function agency_package()
    {
        return $this->belongsTo(AgencyPackage::class, 'agency_package_id', 'id');
    }

    public function agency_package_response()
    {
        return $this->hasOne(AgencyPackageResponse::class, 'agency_package_request_id', 'id');
    }
}
