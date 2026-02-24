<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\PasswordReset
 *
 * @property string $email
 * @property string $token
 * @property string|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset whereToken($value)
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\PasswordReset whereId($value)
 */
class PasswordReset extends Model {
    public $timestamps = false;
    protected $connection = 'mysql';
}
