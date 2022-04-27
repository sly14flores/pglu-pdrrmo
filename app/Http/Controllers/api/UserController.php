<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\User;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Users\UserResourceCollection;

class UserController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Users
     *
     * Users list
     *
     * Paginated list of users
     *
     * @authenticated 
     */
    public function index()
    {

        $users = User::latest()->paginate(10);

        $data = new UserResourceCollection($users);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    private function rules($isNew,$user=null)
    {
        $rules = [
            'firstname' => 'required|string',
            // 'middlename' => 'string',
            'lastname' => 'required|string',
            'email' => ['required', 'string', 'email', 'unique:users'],
            'group_id' => 'string',
        ];

        if (!$isNew) {
            $rules['email'] = Rule::unique('users')->ignore($user);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Users
     * 
     * Add new user
     * 
     * User registration
     * 
     * @bodyParam firstname string required
     * @bodyParam middlename string
     * @bodyParam lastname string
     * @bodyParam email string
     * @bodyParam group_id string
     *
     * @authenticated
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(true));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        DB::beginTransaction();

        try {

            $user = new User;
            $password = Hash::make(env('DEFAULT_PASSWORD','12345678'));
            $data['password'] = $password;
            $data['is_super_admin'] = 0;
            $user->fill($data);

            $user->save();

            /**
             * Send email validation
             */
            $user->sendApiEmailVerificationNotification();

            DB::commit();

            return $this->jsonSuccessResponse(null, 200, "User succesfully added");

        } catch (\Exception $e) {

            DB::rollBack();

            report($e);

			return $this->jsonFailedResponse([
                $e->getMessage()
            ], 500, 'Something went wrong.');

        }

    }

    /**
     * @group Users
     *
     * Get user
     * 
     * Show User Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new UserResource($user);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @group Users
     * 
     * Edit user
     * 
     * Update user information
     * 
     * @bodyParam firstname string required
     * @bodyParam middlename string
     * @bodyParam lastname string
     * @bodyParam email string
     * @bodyParam group_id integer
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$user));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();
        $user->fill($data);
        $user->save();

        return $this->jsonSuccessResponse(null, 200, "User info succesfully updated");
    }

    /**
     * @group Users
     * 
     * Delete User
     * 
     * Delete user information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
			return $this->jsonErrorResourceNotFound();
        }  

        $user->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Users
     * 
     * Batch Delete Users
     * 
     * Delete users information by IDs
     * 
     * @bodyParam ids string[] required
     * 
     * @authenticated
     */
    public function batchDelete(Request $request)
    {

        $rules = [
            'ids' => 'required|array',
        ];

        $messages = [
            'ids.required' => 'No users IDs provided'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        User::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }

}
