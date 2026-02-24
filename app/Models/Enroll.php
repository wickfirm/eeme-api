<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Enroll
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property int $platform_id
 * @property string $social_handle
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll whereSocialHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Enroll whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Enroll extends Model {
    protected $table = 'enrolls';
}
