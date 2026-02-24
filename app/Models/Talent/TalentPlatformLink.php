<?php

namespace App\Models\Talent;

use App\Models\Misc\Platform;
use App\Models\Platform\PlatformEmbed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TalentPlatformLink extends Model
{
    use HasTranslations;
    protected $table = 'talent_platform_link';
    protected $fillable = ['talent_id', 'platform_id', 'link','title','subtitle'];
    public $translatable = [ 'title','subtitle' ];
    public function platform(){
        return $this->hasOne(Platform::class,'id','platform_id');
    }

    public function embed(){
        return $this->hasOne(PlatformEmbed::class,'id','platform_embed_id');
    }

    protected static function booted () {
        static::addGlobalScope ('talent_platform_link', function (Builder $builder) {
            $builder->with (['platform','embed']);
        });
    }
}
