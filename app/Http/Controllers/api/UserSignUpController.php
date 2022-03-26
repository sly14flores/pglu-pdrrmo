<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
     * @bodyParam password_confirmation string
     *
     */
    public function __invoke(Request $request)
    {
        $rules = [
            'firstname' => 'string',
            'middlename' => 'string',
            'lastname' => 'string',
            'email' => ['string', 'email', 'unique:users'],
            'password' => ['required','min:8','confirmed'],
            'password_confirmation' => ['required','string','min:8','same:password'],
            'group_id' => 'integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();
        $data['password'] = Hash::make(env('USER_PASSWORD','12345678'));

        $user = new User;
        $user->fill($data);
        $user->save();

        /**
         * Send email validation
         */

        return $this->jsonSuccessResponse(null, 200, "Registration Successful");

    }
}
