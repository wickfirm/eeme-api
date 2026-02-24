<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Meta\SocialMedia
 *
 * @property int                             $id
 * @property string                          $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|SocialMedia whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class SocialMedia extends Model {
	protected $table = 'social_media';

	protected $fillable = [
		'name',
		'icon',
		'color'
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
