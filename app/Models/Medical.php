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
        'transport',
        'facility_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_covid19' => 'boolean',
    ];

    public function complaints()
    {
        return $this->belongsToMany(Complaint::class, 'medical_complaint', 'medical_id', 'complaint_id')->withTimestamps();
    }

    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'medical_intervention', 'medical_id', 'intervention_id')->withTimestamps();
    }

    public function medics()
    {
        return $this->belongsToMany(User::class, 'medical_medic', 'medical_id', 'user_id')->withTimestamps();
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

}
