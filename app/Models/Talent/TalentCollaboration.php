<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

class TalentCollaboration extends Model
{
    protected $table = 'talents_collaborations';
    protected $fillable = ['talent_id', 'collaboration_id'];
    public function deleteTalentCollaborations( $talent ) {
        $this->where( 'talent_id', $talent->id )->delete();
    }
}
