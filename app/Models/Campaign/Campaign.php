<?php

namespace App\Models\Campaign;


use App\Helpers\Gru;
use App\Models\Talent\TalentCampaign;
use App\Models\Talent\TalentCampaignProof;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Campaign\Campaign
 *
 * @property int    $id
 * @property mixed  $name
 * @property string $brief
 * @property int    $type
 * @property int
 *           $usage_rights
 * @property string
 *           $delivery_date
 * @property int
 *           $extra_settings
 * @property int
 *           $is_draft
 * @property \Illuminate\Support\Carbon|null
 *           $created_at
 * @property \Illuminate\Support\Carbon|null
 *           $updated_at
 * @property-read int|null $influencers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereBrief( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereDeliveryDate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereExtraSettings( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereIsDraft( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereUsageRights( $value )
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Influencer[] $hashtags
 * @property-read int|null       $hashtags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Influencer\Campaign\Mention[]   $mentions
 * @property-read int|null   $mentions_count
 * @property-read array $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|CampaignInfluencer[] $campaign_influencer
 * @property-read int|null $campaign_influencer_count
 * @property-read \Illuminate\Database\Eloquent\Collection|TalentCampaign[] $campaign_talent
 *  @property-read \Illuminate\Database\Eloquent\Collection|TalentCampaignProof[] $talent_campaign_proof
 * @property-read int|null $campaign_talent_count
 * @property-read \Illuminate\Database\Eloquent\Collection|CampaignInfluencer[] $influencers
 * @property-read \Illuminate\Database\Eloquent\Collection|TalentCampaign[] $talents
 * @property-read int|null $talents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereType($value)
 */
class Campaign extends Model {
    use HasTranslations;

    public $translatable = [ 'name' ,'client_name','brand_name' ];

    protected $connection = 'mysql';
    protected $table      = 'campaigns';
    protected $fillable   = [ 'name' , 'brief' , 'usage_rights' , 'delivery_date' , 'extra_settings' ];



    public function talents() {
        return $this->hasMany( TalentCampaign::class );
    }

    public function hashtags() {
        return $this->belongsToMany( Hashtag::class , "campaign_hashtag" )->withTimestamps();
    }

    public function mentions() {
        return $this->belongsToMany( Mention::class , "campaign_mention" )->withTimestamps();
    }

    public function campaign_influencer() {
        return $this->hasMany( CampaignInfluencer::class );
    }

    public function campaign_talent() {
        return $this->hasMany( TalentCampaign::class );
    }

    public function isCampaignComplete() {
        foreach ( $this->campaign_influencer as $campaign_influencer ) {
            if ( $campaign_influencer->status != 3 && $campaign_influencer->status != - 2 ) {
                return false;
            }
        }

        return true;
    }
    public function isCampaignTalentComplete() {
        foreach ( $this->campaign_talent as $campaign_talent ) {
            if ( $campaign_talent->status != Gru::COMPLETE_CAMPAIGN && $campaign_talent->status != Gru::REJECT_CAMPAIGN ) {
                return false;
            }
        }

        return true;
    }
    public function isCampaignInfluencerComplete() {
        foreach ( $this->campaign_influencer as $campaign_influencer ) {
            if ( $campaign_influencer->status != Gru::COMPLETE_CAMPAIGN && $campaign_influencer->status != Gru::REJECT_CAMPAIGN ) {
                return false;
            }
        }

        return true;
    }

}
