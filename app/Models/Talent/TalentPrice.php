<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentPrice
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $talent_id
 * @property int $talent_price_type_id
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice whereTalentPriceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentPrice whereUpdatedAt($value)
 */
class TalentPrice extends Model {
    protected $table = 'talents_prices';
}
