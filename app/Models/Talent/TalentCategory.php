<?php

namespace App\Models\Talent;

use App\Models\Misc\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Talent\TalentCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $talent_id
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Talent\TalentCategory whereUpdatedAt($value)
 */
class TalentCategory extends Pivot {
    protected $table = 'talents_category';
}
