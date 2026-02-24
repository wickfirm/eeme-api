<?php

namespace App\Models\Agency;

use App\Models\Meta\Meta;
use App\Models\Page;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Agency\AgencyPage
 * @property int                             $id
 * @property int|null                        $page_id
 * @property int|null                        $agency_id
 * @property int|null                        $video_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Page|null                  $page
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage whereAgencyId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage wherePageId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyPage whereVideoId( $value )
 * @mixin \Eloquent
 */
class AgencyPage extends Model {
    use HasTranslations;
    protected $table = 'agency_pages';

    public $translatable = ['content' ,'title','subtitle' ];

    public function page() {
        return $this->belongsTo( Page::class );
    }
    public function video() {
        return $this->belongsTo( Video::class );
    }
    public function meta() {
        return $this->hasOne( Meta::class );
    }

    protected static function booted () {
        static::addGlobalScope ('agency_pages', function (Builder $builder) {
            $builder->with (['video', 'page','meta']);
        });
    }
}
