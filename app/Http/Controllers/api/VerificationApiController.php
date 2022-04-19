<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\LoginResource;

use App\Models\User;
use Carbon\Carbon;

use App\Traits\Messages;
use App\Traits\Dumper;

class VerificationApiController extends Controller
{

    use Messages, Dumper;

    public function __construct()
    {
        $this->middleware('signed')->only('verify');
    }

    /**
     * Mark the authenticated user’s email address as verified.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function verify(Request $request)
    {
        $this->dumpToSlack($request['id']);
        $this->dumpToSlack($request->all());

        $userID = $request['id'];
        $user = User::find($userID);

        if (is_null($user)) {
            return $this->jsonErrorResourceNotFound();
        }

        DB::beginTransaction();

        try {

            $user->email_verified_at = Carbon::now();
            $user->save();

            Auth::login($user);
            $token = Auth::user()->createToken('authToken');
            $user->token = $token->accessToken;
            $user->default_password = $user->isDefaultPassword();
            $user->email_verified = $user->isEmailVerified();

            DB::commit();

            return $this->jsonSuccessResponse(new LoginResource($user), 200, 'Your email was successfully verified.');

        } catch (\Exception $e) {

            DB::rollBack();

            report($e);

			return $this->jsonFailedResponse([
                $e->getMessage()
            ], 500, 'Something went wrong.');

        }

    }

    /**
     * Resend the email verification notification.
     * 
     * @authenticated
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request, $id)
    {
        if (!Auth::guard('api')->check()) {
            return $this->jsonErrorUnauthenticated();
        }

        $user = User::find($id);

        if (is_null($user)) {
            return $this->jsonErrorResourceNotFound();
        }

        if ($user->isEmailVerified()) {
            return $this->jsonSuccessResponse(null, 200, 'Email already verified.');
        }

        /** Resend email */
        $user->sendApiEmailVerificationNotification();

        return $this->jsonSuccessResponse(null, 200, 'Resent email confirmation.');
    }

}
