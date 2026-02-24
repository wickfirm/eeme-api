<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentNotify
 *
 * @property int $id
 * @property int $talent_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentNotify whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentNotify extends Model {
    protected $table = 'talents_notify';
}
