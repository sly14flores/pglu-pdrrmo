<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\TraitUuid;
use \OwenIt\Auditing\Contracts\Auditable;

class Complaint extends Model implements Auditable
{
    use HasFactory, TraitUuid, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function medicals()
    {
        return $this->belongsToMany(Medical::class, 'medical_complaint', 'complaint_id', 'medical_id')->withTimestamps();
    }
}
