<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

use App\Traits\TraitUuid;
use \OwenIt\Auditing\Contracts\Auditable;

class Incident extends Mode implements Auditable
{
    use HasFactory, TraitUuid, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'response_type_id',
        'incident_date',
        'incident_time',
        'communication_mode_id',
        'requestor_name',
        'number_of_casualty',
        'incident_status',
        'place_of_incident',
        'barangay',
        'city_municipality',
        'what_happened',
        'facility_referral',
        'time_depart_from_base',
        'time_arrive_at_incident_site',
        'time_depart_from_incident_site',
        'time_arrive_at_facility',
        'time_depart_from_facility',
        'time_arrive_at_base',
        'starting_mileage',
        'incident_site_mileage',
        'ending_mileage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'incident_status' => 'boolean',
        'facility_referral' => 'boolean',
    ];

    public function responseType()
    {
        $this->belongTo(ResponseType::class);
    }

    public function communicationMode()
    {
        $this->belongsTo(CommunicationMode::class);
    }

}
