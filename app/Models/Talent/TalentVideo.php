<?php

namespace App\Models\Talent;

use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentVideo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo query()
 * @mixin \Eloquent
 * @property int                                      $id
 * @property int                                      $talent_id
 * @property int                                      $video_id
 * @property int                                      $is_main
 * @property \Illuminate\Support\Carbon|null          $created_at
 * @property \Illuminate\Support\Carbon|null          $updated_at
 * @property-read \App\Models\Video\Video             $video
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereIsMain( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereTalentId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereVideoId( $value )
 * @property int|null                                 $talent_order_id
 * @property int                                      $is_published
 * @property-read \App\Models\Talent\TalentOrder|null $talent_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereIsPublished( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentVideo whereTalentOrderId( $value )
 */
class TalentVideo extends Model {
    protected $table = 'talents_video';

    public function video() {
        return $this->belongsTo( Video::class );
    }

    public function talent_order() {
        return $this->belongsTo( TalentOrder::class );
    }

    public function talent() {
        return $this->belongsTo( Talent::class );
    }

    protected static function booted () {
        static::addGlobalScope ('talent_video', function (Builder $builder) {
            $builder->with (['video']);
        });
    }
}
