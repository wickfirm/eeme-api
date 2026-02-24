<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Misc\Platform
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Platform whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $translations
 */
class Platform extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $table = 'platforms';

}
