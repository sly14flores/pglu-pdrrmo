<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Traits\Messages;
use App\Models\User;

use App\Http\Resources\UserSignUpResource;

class UserSignUpController extends Controller
{
    use Messages;

    /**
     * User Sign Up
     * 
     * @bodyParam firstname string required
     * @bodyParam middlename string
     * @bodyParam lastname string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required
     *
     */
    public function __invoke(Request $request)
    {
        $rules = [
            'firstname' => 'required|string',
            'middlename' => 'string',
            'lastname' => 'required|string',
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required','min:8','confirmed'],
            'password_confirmation' => ['required','string','min:8','same:password'],
            'group_id' => 'integer',
        ];

        $messages = [
            'email.unique' => 'Email already exists'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();
        $data['is_super_admin'] = 0;
        $data['password'] = Hash::make($data['password']);

        DB::beginTransaction();

        try {

            $user = new User;
            $user->fill($data);
            $user->save();

            /**
             * Send email validation
             */
            $user->sendApiEmailVerificationNotification();

            DB::commit();

            return $this->jsonSuccessResponse(null, 200, "Registration Successful");

        } catch (\Exception $e) {

            DB::rollBack();

            report($e);

			return $this->jsonFailedResponse([
                $e->getMessage()
            ], 500, 'Something went wrong.');

        }

    }
}
