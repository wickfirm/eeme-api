<?php

namespace App\Models\Platform;

use App\Models\Misc\Platform;
use Illuminate\Database\Eloquent\Model;

class PlatformEmbed extends Model
{
    protected $table = 'platform_embed';
    protected $fillable = ['platform_id', 'name', 'iframe'];
    protected $guarded = [];

    public function platform(){
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }
}
