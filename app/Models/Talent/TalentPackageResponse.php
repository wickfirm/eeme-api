<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentPackageResponse
 *
 * @property int $id
 * @property int $talent_package_request_id
 * @property int $payment_status
 * @property mixed $response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Talent\TalentPackageRequest $talent_package_request
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse whereTalentPackageRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackageResponse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentPackageResponse extends Model
{
    protected $table = 'talent_package_responses';
    protected $fillable = ['talent_package_request_id', 'payment_status', 'response'];

    public function talent_package_request() {
        return $this->belongsTo( TalentPackageRequest::class , 'talent_package_request_id', 'id');
    }

}
