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
    protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'medical_intervention', 'medical_id', 'intervention_id')->withTimestamps();
    }

    public function medics()
    {
        return $this->belongsToMany(User::class, 'medical_medic', 'medical_id', 'user_id')->withTimestamps();
    }

}
