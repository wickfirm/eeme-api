<?php

namespace App\Models\Charity;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Charity\Charity
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $description
 * @property string|null                     $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity whereDescription( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity whereImage( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charity\Charity whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class Charity extends Model {
    use HasTranslations;

    protected $table = 'charities';

    public $translatable = [ 'name', 'description' ];
}
