<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Support\Facades\Hash;

use App\Traits\TraitUuid;
use \OwenIt\Auditing\Contracts\Auditable;

use App\Notifications\VerifyApiEmail;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, TraitUuid, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'is_super_admin',
        'email',
        'password',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
    ];

    /**
     * Send email verification
     */
    public function sendApiEmailVerificationNotification()
    {
		$when = now()->addSeconds(5);
        $this->notify((new VerifyApiEmail)->delay($when));
    }

    public function isEmailVerified()
    {
        return $this->email_verified_at != null;
    }

    public function isDefaultPassword()
    {
        return Hash::check(env('DEFAULT_PASSWORD',12345678),$this->password);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
