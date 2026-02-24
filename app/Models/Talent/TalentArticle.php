<?php

namespace App\Models\Talent;

use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\Translatable\HasTranslations;

class TalentArticle extends Model  {
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
}
