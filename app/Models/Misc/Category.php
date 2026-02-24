<?php

namespace App\Models\Misc;

use App\Models\Page;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentCategory;
use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Misc\Category
 *
 * @property int                                                                       $id
 * @property string                                                                    $name
 * @property string                                                                    $slug
 * @property \Illuminate\Support\Carbon|null                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                           $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category whereSlug( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Misc\Category whereUpdatedAt( $value )
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\Talent[] $talents
 * @property-read int|null                                                             $talents_count
 * @property-read mixed $translations
 */
class Category extends Model {
    use Sluggable, HasTranslations;

    public $translatable = [ 'name' ,'title' ];

    protected $table = 'categories';

    public function talents() {
        return $this->belongsToMany( Talent::class, 'talents_category' );
    }
    public function page() {
        return $this->belongsTo( Page::class);
    }

    public function sluggable(): array {
        return [
            'source' => 'name'
        ];
    }
}
