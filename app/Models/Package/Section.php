<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Package\Section
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package\Package[] $packages
 * @property-read int|null $packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package\Service[] $services
 * @property-read int|null $services_count
 * @method static \Illuminate\Database\Eloquent\Builder|Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = ['name'];

    public function packages()
    {
        return $this->belongsToMany(Package::class, "package_section")->withTimestamps();
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, "section_service")->withTimestamps();
    }

    public function hasService(Service $service)
    {
        if (empty($this->services->find($service->id))) {
            return false;
        }
        return true;
    }
}
