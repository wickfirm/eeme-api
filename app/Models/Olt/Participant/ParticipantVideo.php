<?php

namespace App\Models\Olt\Participant;

use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Model;

class ParticipantVideo extends Model
{
    protected $table = 'participants_video';

    public function video() {
        return $this->belongsTo( Video::class );
    }
}
