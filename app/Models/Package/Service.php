<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Package\Service
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int|null $addon_type 0 is for Personalized | 1 is for Business
 * @property int|null $type_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package\Package[] $package
 * @property-read int|null $package_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package\Section[] $section
 * @property-read int|null $section_count
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereAddonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereTypeLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['name', 'type', 'addon_type', 'type_limit'];

    public function section() {
        return $this->belongsToMany(Section::class, "section_service")->withTimestamps();
    }

    public function package() {
        return $this->belongsToMany(Package::class, "package_service")->withTimestamps();
    }
}
