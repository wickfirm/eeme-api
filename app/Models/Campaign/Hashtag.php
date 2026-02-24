<?php

namespace App\Models\Campaign;


use App\Models\Campaign\Campaign;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Campaign\Hashtag
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hashtag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Hashtag extends Model
{
    protected $connection = 'mysql';
    protected $table = 'hashtags';
    protected $fillable = ['name'];

    public function campaigns() {
        return $this->hasManyThrough(Campaign::class, 'campaign_hashtag');
    }
}
