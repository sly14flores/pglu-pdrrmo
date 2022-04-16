<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\PasswordReset;

use App\Models\User;

use App\Traits\Messages;

class ResetPasswordController extends Controller
{

    use Messages;

    private $http_code_ok;
    private $http_code_error; 

    public function __construct()
    {
        $this->middleware(['guest'])->only(['sendResetLinkEmail']);

        $this->http_code_ok = 200;
        $this->http_code_error = 500;   
    }

    /**
     * @group Passwords
     * 
     * Send email reset link
     * 
     * @bodyParam email string required
     * 
     * @unauthenticated
     */
    public function sendResetLinkEmail(Request $request)
    {

        $validator = Validator::make($request->all(), ['email' => 'required|email']);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        };        

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->jsonSuccessResponse(null, $this->http_code_ok, __($status))
            : $this->jsonFailedResponse(null, 422, __($status));

    }

	public function validateToken(Request $request)
	{
		
		$rules = [
            'id' => ['required'],
            'token' => ['required']
		];
		
		$data = $request->validate($rules);
		
		$user = User::find($data['id']);
		
        $validate_token = $this->broker()->tokenExists($user, $data['token']);
		
		if (!$validate_token) {
            return $this->jsonFailedResponse(null, 422, 'Invalid token.');
		}
		
		return $this->jsonSuccessResponse(null, $this->http_code_ok, "Token is valid.");
		
	}

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
	
    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
	
    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
			return $this->jsonSuccessResponse(null, $this->http_code_ok, trans($response));			
        }
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {		
            throw ValidationException::withMessages([
                'error' => [trans($response)],
            ]);
        }
    }

	public function reset(Request $request)
	{
		
		$data = $request->validate($this->rules(), $this->messages());

		/**
		 * Validate if password is same as old password
		 */
		$user = User::where([['id',$request->id],['email',$data['email']]])->first();
		
		if (is_null($user)) {
			throw ValidationException::withMessages([
				'error' => 'Email provided does not match with token',
			]);	
		};
		
		$same_password = Hash::check($data['password'],$user->password);
		
		if ($same_password)
		{
			throw ValidationException::withMessages([
				'error' => 'Password cannot be the same with old password.',
			]);
		}

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->save();

				// $user->setRememberToken(Str::random(60));

				event(new PasswordReset($user));
			}
		);

        return $status == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $status)
                    : $this->sendResetFailedResponse($request, $status);

    }
    
    /**
     * @group Passwords
     * 
     * Reset Password
     * 
     * @bodyParam email string required
     * @bodyParam token string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required
     * 
     * @unauthenticated
     */
	public function resetPassword(Request $request)
	{
        
        $validator = Validator::make($request->all(), $this->rules());
        
        if ($validator->fails()) {
            return $this->jsonFailedResponse(null, 422, $validator->errors());
        };

        $data = $validator->valid();

		$user = User::where('email',$request->email)->first();
		
		if (is_null($user)) {
            return $this->jsonFailedResponse(null, 422, 'User does not exist.');
		};
        
		/**
		 * Validate if password is same as old password
		 */        
		$same_password = Hash::check($data['password'],$user->password);
		
		if ($same_password) {
            return $this->jsonFailedResponse(null, 422, 'Password cannot be the same with old password.');
		}

        $payload = $request->only('password', 'password_confirmation', 'token', 'email');

		$status = Password::reset(
			$payload,
			function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->save();

				// $user->setRememberToken(Str::random(60));

				event(new PasswordReset($user));
			}
		);

        return $status == Password::PASSWORD_RESET
                    ? $this->jsonSuccessResponse(null, $this->http_code_ok, __($status))
                    : $this->jsonFailedResponse(null, 422, __($status));

	}
	
	public function rules()
	{
		return [
            'token' => ['required'],		
            'email' => ['required','email'],
            'password' => ['required','min:8','confirmed'],
            'password_confirmation' => ['required','string','min:8','same:password']
        ];
	}
	
	public function messages()
	{
		return [
			'token.required' => 'No token provided',
			'password.required' => 'Password is required',
			'password_confirmation.required' => 'Please confirm password',
			'password_confirmation.same' => 'Password confirmation does not match'		
		];
	}

}
