<?php

namespace App\Models\Campaign;

use App\Models\Campaign\Campaign;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Campaign\Mention
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Mention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mention query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mention whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mention whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mention whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mention whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mention extends Model
{
    protected $connection = 'mysql';
    protected $table = 'mentions';
    protected $fillable = ['name'];

    public function campaigns() {
        return $this->hasManyThrough(Campaign::class, 'campaign_hashtag');
    }
}
