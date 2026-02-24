<?php

namespace App\Models\Talent;

use App\Models\Order\OrderResponse;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Talent\TalentOrder
 * @property int                                  $id
 * @property int                                  $talent_id
 * @property int                                  $order_response_id
 * @property string|null                          $first_reminder
 * @property int                                  $first_reminder_is_sent
 * @property string|null                          $due_date
 * @property int                                  $is_sent
 * @property \Illuminate\Support\Carbon|null      $created_at
 * @property \Illuminate\Support\Carbon|null      $updated_at
 * @property-read \App\Models\Order\OrderResponse $order_response
 * @property-read \App\Models\Talent\Talent       $talent
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereFirstReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereFirstReminderIsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereIsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereOrderResponseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentOrder extends Model {
    protected $table = 'talents_order';

    public function talent() {
        return $this->belongsTo( Talent::class );
    }

    public function order_response() {
        return $this->belongsTo( OrderResponse::class );
    }
    public function talent_videos() {
        return $this->hasMany( TalentVideo::class , 'talent_order_id', 'id')->where('is_published' , 1);
    }

}
