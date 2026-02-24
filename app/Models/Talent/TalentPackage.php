<?php

namespace App\Models\Talent;

use App\Models\Package\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Talent\TalentPackage
 *
 * @property int $id
 * @property int $talent_id
 * @property int $package_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Talent\Talent $talent
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $is_active
 * @property-read Package $package
 * @property-read TalentPackagePayment|null $talent_package_payment
 * @method static \Illuminate\Database\Eloquent\Builder|TalentPackage whereIsActive($value)
 */
class TalentPackage extends Pivot
{
    protected $table = 'talent_package';
    protected $fillable = ['id', 'talent_id', 'package_id', 'is_active'];
    public $incrementing = true;

    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function package() {
        return $this->belongsTo( Package::class );
    }
    public function talent_package_payment(){
        return $this->hasOne(TalentPackageRequest::class, 'talent_package_id', 'id');
    }
}
