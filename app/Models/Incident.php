<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

use App\Traits\TraitUuid;
use \OwenIt\Auditing\Contracts\Auditable;

class Incident extends Model implements Auditable
{
    use HasFactory, TraitUuid, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'incident_type_id',
        'response_type_id',
        'incident_date',
        'incident_time',
        'communication_mode_id',
        'requestor_name',
        'number_of_casualty',
        'incident_status',
        'landmark',
        'street_purok_sitio',
        'barangay',
        'city_municipality',
        'province',
        'region',
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

    public function incidentType()
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function responseType()
    {
        return $this->belongsTo(ResponseType::class);
    }

    public function communicationMode()
    {
        return $this->belongsTo(CommunicationMode::class);
    }

    public function agencies()
    {
        return $this->belongsToMany(Agency::class, 'incident_agency', 'incident_id', 'agency_id')->withTimestamps();
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'incident_facility', 'incident_id', 'facility_id')->withTimestamps();
    }

    /**
     * Staffs deployed
     */
    public function staffs()
    {
        return $this->belongsToMany(User::class, 'incident_staff', 'incident_id', 'user_id')->withTimestamps();
    }

    /**
     * Agents
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'incident_agent', 'incident_id', 'user_id')->withTimestamps();
    }

    /**
     * Deployed vehicles
     */
    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'incident_vehicle', 'incident_id', 'vehicle_id')->withTimestamps();
    }

    /**
     * Medicals
     */
    public function medical()
    {
        return $this->hasOne(Medical::class);
    }

}
