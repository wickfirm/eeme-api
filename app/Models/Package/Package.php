<?php

namespace App\Models\Package;

use App\Models\Talent\Talent;
use App\Models\Talent\TalentPackage;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Package\Package
 *
 * @property int $id
 * @property string $name
 * @property float $number
 * @property int $type
 * @property mixed $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package\Section[] $sections
 * @property-read int|null $sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package\Service[] $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Talent[] $talents
 * @property-read int|null $talents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $annual_savings
 * @property int|null $annual_savings_type type=0 -> dollars, type=1 ->percentage
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAnnualSavings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAnnualSavingsType($value)
 */
class Package extends Model
{
    protected $table = 'packages';
    protected $fillable = ['name', 'number', 'type', 'description', 'annual_savings', 'annual_savings_type'];

    protected $appends = [ 'name_package' ];
    public function getNamePackageAttribute(){
        if($this->number == 0 ){
            return 'Free';
        }else{
            return '$' . $this->number .'/Month';
        }

    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, "package_section")->withTimestamps();
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, "package_service")->withTimestamps();
    }

    public function talents()
    {
        return $this->hasManyThrough(Talent::class, TalentPackage::class, 'package_id', 'id');
    }

    public function hasService(Service $service)
    {
        if (empty($this->services->find($service->id))) {
            return false;
        }
        return true;
    }
}
