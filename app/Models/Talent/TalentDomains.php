<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

class TalentDomains extends Model
{
    protected $table = 'talent_domains';
    protected $fillable = ['id', 'talent_id', 'domain_name'];

    public function talent() {
        return $this->belongsTo(Talent::class);
    }
}
