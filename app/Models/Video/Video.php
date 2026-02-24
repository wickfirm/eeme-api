<?php

namespace App\Models\Video;

use App\Models\Talent\TalentVideo;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\video\video
 *
 * @property int                             $id
 * @property string                          $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video whereLink( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video\Video whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class Video extends Model {
    protected $table = 'videos';
}
