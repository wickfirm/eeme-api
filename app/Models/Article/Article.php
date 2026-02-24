<?php

namespace App\Models\Article;

use App\Models\Paudio\PaudioResponse;
use App\Models\Talent\ArticleTalent;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentArticle;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\FeedItem;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Article\Article
 *
 * @property int                                                           $id
 * @property int|null                                                      $talent_id
 * @property array|null                                                    $title
 * @property string|null                                                   $image
 * @property int|null                                                      $video_id
 * @property array|null                                                    $description
 * @property array|null                                                    $slug
 * @property int                                                           $is_published
 * @property int                                                           $type
 * @property \Illuminate\Support\Carbon|null                               $created_at
 * @property \Illuminate\Support\Carbon|null                               $updated_at
 * @property-read array                                                    $translations
 * @property-read PaudioResponse|null                                      $paudio
 * @property-read Talent|null                                              $talent
 * @property-read \Illuminate\Database\Eloquent\Collection|ArticleTalent[] $talents
 * @property-read int|null                                                 $talents_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Talent[]        $talents_articles
 * @property-read int|null                                                 $talents_articles_count
 * @property-read Video|null                                               $video
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImage( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsPublished( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTalentId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereType( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereVideoId( $value )
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasTranslations;

    public $translatable = [ 'title' , 'description' , 'slug' ];

    protected $table = 'articles';

    public function talent() {
        return $this->belongsTo( Talent::class );
    }

    public function video() {
        return $this->belongsTo( Video::class );
    }



    public function talents() {
        return $this->hasMany( ArticleTalent::class , 'article_id' );
    }



    public function randomTalent(){

        return $this->belongsToMany( 'App\Models\Talent\Talent' , 'talent_articles' , 'article_id' , 'talent_id' )
            ->inRandomOrder()->first();
    }


    public function talents_articles() {
        return $this->belongsToMany( 'App\Models\Talent\Talent' , 'talent_articles' , 'article_id' , 'talent_id' );
    }
    public function paudio(){
        return $this->hasOne(PaudioResponse::class );

    }

    protected static function booted () {
        static::addGlobalScope ('article', function (Builder $builder) {
            $builder->with (['talent']);
        });
    }
}
