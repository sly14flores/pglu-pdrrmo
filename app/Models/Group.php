<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\TraitUuid;
use \OwenIt\Auditing\Contracts\Auditable;

class Group extends Model implements Auditable
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
        'role'
    ];
}
