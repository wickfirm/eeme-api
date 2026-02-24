<?php

namespace App\Models\Agency;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Agency\AgencyPackageResponse
 *
 * @property int $id
 * @property int $agency_package_request_id
 * @property int $payment_status
 * @property mixed $response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency\AgencyPackageRequest $agency_package_request
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse whereAgencyPackageRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackageResponse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AgencyPackageResponse extends Model
{
    protected $table = 'agency_package_responses';
    protected $fillable = ['agency_package_request_id', 'payment_status', 'response'];

    public function agency_package_request() {
        return $this->belongsTo( AgencyPackageRequest::class , 'agency_package_request_id', 'id');
    }
}
