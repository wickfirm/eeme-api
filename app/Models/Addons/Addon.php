<?php

namespace App\Models\Addons;
use App\Models\Talent\Talent;
use Spatie\Translatable\HasTranslations;
use App\Models\Talent\TalentAddons;
use Illuminate\Database\Eloquent\Builder;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Addons\Addon
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $description
 * @property int                             $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon whereSlug( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Addons\Addon whereUpdatedAt( $value )
 * @mixin \Eloquent
 * @property-read mixed $translations
 */

class Addon extends Model
{
    use HasTranslations;


    protected $guarded = [] ;

    public $translatable = [ 'name', 'description' ];

    protected $table = 'addons';

    protected $appends = [ 'category_name' ];

    public function talents(){
    	return $this->belongsToMany(Talent::class, 'talent_addons', 'addons_id', 'talent_id');
    }
    public function category_addon () {

        return $this->belongsTo(CategoryAddons::class  );
    }

    public function talent_addon(){
        return $this->hasMany(TalentAddons::class ,'addons_id' , 'id');
    }

    public function getCategoryNameAttribute(){
        if($this->category_addon_id != null ){
            return $this->category_addon->name;
        }
        else{
            return "";
        }
    }

    protected static function booted () {
        static::addGlobalScope ('addons', function (Builder $builder) {
            $builder->with (['category_addon']);
        });
    }

}
