<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Addons\Addon;

/**
 * App\Models\Talent\TalentAddons
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $talent_id
 * @property int $addons_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons whereAddonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentAddons whereUpdatedAt($value)
 */
class TalentAddons extends Model
{
    protected $table = 'talent_addons';
    protected $guarded = [];

    public function deleteTalentAddons( $talent ) {
        $this->where( 'talent_id', $talent->id )->delete();
    }

    public function addons(){
        return $this->belongsTo(Addon::class, 'addons_id');
    }

    public function talent(){
        return $this->belongsTo(Addon::class);
    }
}
