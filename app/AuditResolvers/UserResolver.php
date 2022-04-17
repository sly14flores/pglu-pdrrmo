<?php

namespace App\AuditResolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

use Illuminate\Support\Facades\Auth;

class UserResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        // TODO: Implement resolve() method.
        $user = Auth::guard('api')->user();

        return $user->id ?? null;
    }
}
