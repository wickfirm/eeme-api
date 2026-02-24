<?php

namespace App\Models\Talent;

use App\Models\Addons\Addon;
use Illuminate\Database\Eloquent\Model;


class TalentVerifyContact extends Model
{
    protected $table = 'talents_verify_contact';

    protected $fillable = [
        'talent_id',
        'email',
        'number',
    ];


    public function talent(){
        return $this->belongsTo(Talent::class);
    }
}
