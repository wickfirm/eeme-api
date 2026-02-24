<?php

namespace App\Models\User;

use App\Models\Talent\Talent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User\User
 *
 * @property int                                                                                  $id
 * @property string                                                                               $name
 * @property string|null                                                                          $email
 * @property string|null                                                                          $email_verified_at
 * @property string|null                                                                          $password
 * @property string|null                                                                          $remember_token
 * @property \Illuminate\Support\Carbon|null                                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                                      $updated_at
 * @property-read mixed                                                                           $initials
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null                                                                        $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[]       $roles
 * @property-read int|null                                                                        $roles_count
 * @property-read \App\Models\Talent\Talent                                                       $talent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User permission( $permissions )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User role( $roles, $guard = null )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEmail( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEmailVerifiedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePassword( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereRememberToken( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereUpdatedAt( $value )
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\UserFollow[]          $follows
 * @property-read int|null                                                                        $follows_count
 * @property-read mixed                                                                           $translations
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 */
class User extends Authenticatable {
    use HasRoles, HasTranslations,Notifiable;
    protected $table = 'users';

    protected $fillable = [ 'name', 'email', 'password' ];

    protected $appends = [ 'initials' ];

    public $translatable = [ 'name' ];

    public function getInitialsAttribute() {
        $name       = $this->name;
        $name_array = explode( " ", $name );
        $initials   = '';
        foreach ( $name_array as $name_word ) {
            $initials .= substr( $name_word, 0, 1 );
        }

        return $initials;
    }

    public function is_following( $talent_id ) {
        $follow_exist = $this->follows->where( 'talent_id', $talent_id )->first();

        $this->attributes['is_following'] = $follow_exist ? true : false;
    }

    public function setPasswordAttribute( $password ) {
        $this->attributes['password'] = Hash::make( $password );
    }

    public function talent() {
        return $this->hasOne( Talent::class );
    }

    public function follows() {
        return $this->hasMany( UserFollow::class );
    }
}
