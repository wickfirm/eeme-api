<?php

namespace App\Models\Agency;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Agency\AgencyCredentials
 *
 * @property int $id
 * @property mixed $name
 * @property mixed $slug
 * @property mixed $description
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agency\Agency $agency
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @property string|null $slug_ar
 * @property mixed|null $description_ar
 * @property string|null $image_ar
 * @property int $is_active
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereDescriptionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereImageAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereSlugAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereUserId($value)
 * @property int $number
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereNumber($value)
 * @property int $is_published
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyCredentials whereIsPublished($value)
 */
class AgencyCredentials extends Model
{
    protected $table = 'agencies';
    protected $fillable = ['credentials'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
