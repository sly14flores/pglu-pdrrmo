<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

use App\Traits\TraitUuid;
use \OwenIt\Auditing\Contracts\Auditable;

class Medical extends Model implements Auditable
{
    use HasFactory, TraitUuid, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'incident_id',
        'noi_moi',
        'is_covid19',
        'patient_name',
        'age',
        'gender',
        'region',
        'province',
        'city_municipality',
        'barangay',
        'street_purok_sitio',
        'transport_type_id',
        'facility_id',
        'complaints',
        'interventions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_covid19' => 'boolean',
    ];

    public function transportType()
    {
        return $this->belongsTo(TransportType::class, 'transport_type_id');
    }

    /**
     * Receiving facility
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    // public function complaints()
    // {
    //     return $this->belongsToMany(Complaint::class, 'medical_complaint', 'medical_id', 'complaint_id')->withTimestamps();
    // }

    // public function interventions()
    // {
    //     return $this->belongsToMany(Intervention::class, 'medical_intervention', 'medical_id', 'intervention_id')->withTimestamps();
    // }

    public function medics()
    {
        return $this->belongsToMany(User::class, 'medical_medic', 'medical_id', 'user_id')->withTimestamps();
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function medicalRegion()
    {
        return $this->belongsTo(PhilippineRegion::class, 'region', 'region_code');
    }

    public function medicalProvince()
    {
        return $this->belongsTo(PhilippineProvince::class, 'province', 'province_code');
    }

    public function medicalCity()
    {
        return $this->belongsTo(PhilippineCity::class, 'city_municipality', 'city_municipality_code');
    }

    public function medicalBarangay()
    {
        return $this->belongsTo(PhilippineBarangay::class, 'barangay', 'barangay_code');
    }

}
