<?php

namespace App\Models\Talent;

use App\Models\Influencer\Campaign\Campaign;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Talent\TalentCampaign
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $talent_id
 * @property int $fee
 * @property string|null $custom_delivery_date
 * @property string|null $custom_brief
 * @property int $status
 * @property string|null $talent_reason
 * @property string|null $review
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Campaign $campaign
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Talent\TalentCampaignProof[]$proof
 * @property-read int|null $proof_count
 * @property-read \App\Models\Talent\Talent $talent
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign query()
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereCustomBrief($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereCustomDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereTalentReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TalentCampaign whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentCampaign extends Model {
    protected $connection = 'mysql';
    protected $table      = 'talents_campaign';
    protected $fillable   = [
        'talent_id' ,
        'campaign_id' ,
        'fee' ,
        'custom_delivery_date' ,
        'custom_brief' ,
        'status' ,
        'talent_reason' ,
        'review'
    ];

    public function campaign() {
        return $this->belongsTo( Campaign::class );
    }

    public function talent() {
        return $this->belongsTo( talent::class );
    }

    public function proof() {
        return $this->hasOne( TalentCampaignProof::class );
    }
}
