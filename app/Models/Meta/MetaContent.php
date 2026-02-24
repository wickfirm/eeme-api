<?php

namespace App\Models\Meta;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class MetaContent extends Model
{
    protected $table = 'meta_content';
    public function page() {
        return $this->belongsTo( Page::class);
    }
}
