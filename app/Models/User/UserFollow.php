<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\UserFollow
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $talent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\UserFollow whereUserId($value)
 */
class UserFollow extends Model {
    protected $table = 'users_follow';
}
