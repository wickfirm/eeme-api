<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Misc\Occasion
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Occasion whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $translations
 */
class Occasion extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $table = 'occasions';
}
