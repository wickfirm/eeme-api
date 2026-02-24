<?php

namespace App\Models\Talent;

use App\Helpers\Gru;
use App\Helpers\Minion;
use App\Models\Agency\Agency;
use App\Models\Article\Article;
use App\Models\Charity\Charity;
use App\Models\Misc\Category;
use App\Models\Misc\Collaboration;
use App\Models\Misc\SocialMedia;
use App\Models\Package\Package;
use App\Models\Traits\Sluggable;
use App\Models\User\User;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use \Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

/**
 * App\Models\Talent\Talent
 * @property int                                                                                      $id
 * @property int                                                                                      $user_id
 * @property string|null                                                                              $country
 * @property int|null                                                                                 $added_by_user_id
 * @property string|null                                                                              $number
 * @property string|null                                                                              $image
 * @property string|null
 *           $talent_name_image_en
 * @property string|null
 *           $talent_name_image_ar
 * @property array|null                                                                               $description
 * @property string|null                                                                              $talent_logo
 * @property int|null                                                                                 $response_time
 * @property int                                                                                      $is_available
 * @property int                                                                                      $is_active
 * @property int                                                                                      $is_published
 * @property array                                                                                    $slug
 * @property \Illuminate\Support\Carbon|null                                                          $created_at
 * @property \Illuminate\Support\Carbon|null                                                          $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Talent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Talent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Talent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereAddedByUserId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereCountry( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereDescription( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereImage( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereIsActive( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereIsAvailable( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereIsPublished( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereNumber( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereResponseTime( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereSlug( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereTalentLogo( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereTalentNameImageAr( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereTalentNameImageEn( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Talent whereUserId( $value )
 * @property-read \Illuminate\Database\Eloquent\Collection|Agency[]                                   $agency
 * @property-read int|null                                                                            $agency_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[]                                 $categories
 * @property-read int|null                                                                            $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Charity[]                                  $charities
 * @property-read int|null                                                                            $charities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentFilmography[]     $filmography
 * @property-read int|null
 *                $filmography_count
 * @property-read array                                                                               $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentPlatformLink[]    $platforms
 * @property-read int|null                                                                            $platforms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentPrice[]           $price
 * @property-read int|null                                                                            $price_count
 * @property-read \Illuminate\Database\Eloquent\Collection|SocialMedia[]                              $social
 * @property-read int|null                                                                            $social_count
 * @property-read \App\Models\Talent\TalentArticle|null                                               $talentArticle
 * @property-read \App\Models\Talent\ArticleTalent|null
 *                $talentLatestArticle
 * @property-read \App\Models\Talent\TalentVideo|null                                                 $talentVideo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentArticle[]         $talent_articles
 * @property-read int|null
 *                $talent_articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentDonation[]        $talent_donation
 * @property-read int|null
 *                $talent_donation_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentFilmographyCast[]
 *                $talent_filmography_cast
 * @property-read int|null
 *                $talent_filmography_cast_count
 * @property-read \App\Models\Talent\TalentMeta|null                                                  $talent_meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentOrderType[]
 *                $talent_order_types
 * @property-read int|null
 *                $talent_order_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentPackage[]         $talent_package
 * @property-read int|null
 *                $talent_package_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentSocial[]          $talent_social
 * @property-read int|null
 *                $talent_social_count
 * @property-read User                                                                                $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentVideo[]           $videos
 * @property-read int|null                                                                            $videos_count
 * @mixin \Eloquent
 */
class Talent extends Model {
    use HasTranslations;

    public $translatable = [ 'description' , 'slug' ];

    protected $fillable = [ 'user_id' ];

    protected $table = 'talents';


    protected $appends = [
        'order_count' ,
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function talent_order_types() {
        return $this->hasMany( TalentOrderType::class );
    }

    public function price() {
        return $this->hasOne( TalentPrice::class );
    }

    public function videos() {
        return $this->hasMany( TalentVideo::class );
    }

    public function categories() {
        return $this->belongsToMany( Category::class , 'talents_category' );
    }

    public function articles() {
        return $this->hasOne( Article::class , 'articles' );
    }

    public function talent_articles() {
        return $this->hasMany( TalentArticle::class );
    }

    public function charities() {
        return $this->belongsToMany( Charity::class , 'talents_charity' );
    }

    public function talentArticle() {
        return $this->hasOne( TalentArticle::class )->where( 'is_published' , 1 )->latest();
    }


    public function talentVideo() {
            return $this->hasMany( TalentVideo::class )->where( 'is_published' , 1 )
            ->where('is_main',0)
            ->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
            $query->where( 'is_public' , 1 );
        } );
    }

    public function talent_donation() {
        return $this->hasMany( TalentDonation::class );
    }

    public function platforms() {
        return $this->hasMany( TalentPlatformLink::class , 'talent_id' , 'id' )->where('is_published',1);
    }

    public function spot_platforms() {
        return $this->hasMany( TalentPlatformLink::class , 'talent_id' , 'id' )->where('is_published',1)->where('type' , Gru::IN_THE_SPOT);
    }

    public function media_platforms() {
        return $this->hasMany( TalentPlatformLink::class , 'talent_id' , 'id' )->where('is_published',1)->where('type' , Gru::IN_THE_MEDIA)->orderBy ('priority');
    }

    public function filmography() {
        return $this->hasMany( TalentFilmography::class , "talent_id" , "id" );
    }

    public function talent_meta() {
        return $this->hasOne( TalentMeta::class , 'talent_id' , 'id' );
    }

    public function talent_filmography_cast() {
        return $this->hasMany( TalentFilmographyCast::class , "talent_id" , "id" )
            ->wherehas('filmography',function($query){
            $query->where('is_published' , 1);
        })  ;
    }
    public function collaborations() {
        return $this->belongsToMany( Collaboration::class , 'talents_collaborations' );
    }
    public function social() {
        return $this->belongsToMany( SocialMedia::class , 'talents_social' );
    }

    public function talent_social() {
        return $this->hasMany( TalentSocial::class );
    }

    public function featured() {
        return $this->hasOne( TalentFeatured::class );
    }

    public function talent_package() {
        return $this->hasMany( TalentPackage::class );
    }

    public function talentLatestArticle() {
        return $this->hasOne( ArticleTalent::class )->whereHas( 'article' , function ( $query ) {
            $query->where( 'is_published' , 1 );
        } )->latest();
    }



    public function getOrderCountAttribute() {
        return $this->talent_order_types->count();
    }


    public function agency() {
        return $this->belongsToMany( Agency::class , 'agency_talents' );
    }

    protected static function booted () {
        static::addGlobalScope ('talent', function (Builder $builder) {
            $builder->with (['user','categories']);
        });
    }

}

