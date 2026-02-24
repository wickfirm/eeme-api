<?php

namespace App\Models\Agency;

use App\Models\Package\Package;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Agency\AgencyPackage
 *
 * @property int $id
 * @property int $agency_id
 * @property int $package_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency\Agency $agency
 * @property-read \App\Models\Agency\AgencyPackageRequest|null $agency_package_payment
 * @property-read Package $package
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AgencyPackage extends Model
{
    protected $table = 'agency_package';
    protected $fillable = ['id', 'agency_id', 'package_id', 'is_active'];
    public $incrementing = true;

    public function agency() {
        return $this->belongsTo( Agency::class );
    }
    public function package() {
        return $this->belongsTo( Package::class );
    }
    public function agency_package_payment(){
        return $this->hasOne(AgencyPackageRequest::class, 'agency_package_id', 'id');
    }
}
