<?php

namespace App\Models\Talent;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentPriceTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPriceTypes whereUpdatedAt($value)
 */
class TalentPriceTypes extends Model {
    use Sluggable;

    protected $table = 'talents_price_types';

    public function sluggable(): array {
        return [
            'source' => 'name'
        ];
    }
}
