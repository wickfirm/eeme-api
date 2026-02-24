<?php

namespace App\Models\Agency;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Agency\AgencyDomain
 *
 * @property int                                 $id
 * @property int|null                            $agency_id
 * @property string                              $domain_name
 * @property \Illuminate\Support\Carbon|null     $created_at
 * @property \Illuminate\Support\Carbon|null     $updated_at
 * @property-read \App\Models\Agency\Agency|null $agency
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain whereAgencyId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain whereDomainName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|AgencyDomain whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class AgencyDomain extends Model {

    protected $table    = 'agencies_domain';
    protected $fillable = [ 'id' , 'agency_id' , 'domain_name' ];

    public function agency() {
        return $this->belongsTo( Agency::class );
    }

}
