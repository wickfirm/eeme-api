<?php

namespace App\Models\Agency;

use App\Models\Talent\Talent;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Agency\Agency
 *
 * @property int $id
 * @property mixed $name
 * @property mixed $slug
 * @property mixed $description
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency\AgencyCredentials|null $credentials
 * @method static \Illuminate\Database\Eloquent\Builder|Agency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @property string|null $slug_ar
 * @property mixed|null $description_ar
 * @property string|null $image_ar
 * @property int $is_active
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereDescriptionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereImageAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereSlugAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereUserId($value)
 * @property-read array $translations
 * @property int $number
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereNumber($value)
 * @property int $is_published
 * @method static \Illuminate\Database\Eloquent\Builder|Agency whereIsPublished($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Talent[] $talents
 * @property-read int|null $talents_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agency\AgencyPackage[] $agency_package
 * @property-read int|null $agency_package_count
 */
class Agency extends Model
{
    protected $table = 'agencies';
    protected $fillable = ['user_id', 'slug', 'number', 'description', 'image', 'image_ar', 'is_active', 'is_published'];

    use HasTranslations;

    public $translatable = ['description', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function credentials()
    {
        return $this->hasOne(AgencyCredentials::class);
    }

    public function talents()
    {
        return $this->belongsToMany(Talent::class,'agency_talents');
    }

    public function agency_package()
    {
        return $this->hasMany(AgencyPackage::class);
    }
    public function orders(){
        return $this->talents->orders;
    }
    public function domain(){
        return $this->hasOne(AgencyDomain::class);
    }
}
