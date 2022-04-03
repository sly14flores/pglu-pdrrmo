<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\Group;
use App\Http\Resources\Groups\GroupResource;
use App\Http\Resources\Groups\GroupResourceCollection;

class GroupController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Groups
     *
     * Groups list
     *
     * Paginated list of groups
     *
     * @authenticated 
     */
    public function index()
    {

        $groups = Group::latest()->paginate(10);

        $data = new GroupResourceCollection($groups);

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

    private function rules($isNew,$group=null)
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'string',
        ];

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Groups
     * 
     * Add new group
     * 
     * Group input
     *
     * @bodyParam name string required
     * @bodyParam description string
     *
     * @authenticated
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(true));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();

        $group = new Group;
        $group->fill($data);
        $group->save();

        return $this->jsonSuccessResponse(null, 200, "Group succesfully added");
    }

    /**
     * @group Groups
     *
     * Get group
     * 
     * Show Group Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $group = Group::find($id);

        if (is_null($group)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new GroupResource($group);

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
     * @group Groups
     * 
     * Edit group
     * 
     * Update group information
     *
     * @bodyParam name string required
     * @bodyParam description string
     *
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        if (is_null($group)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$group));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();
        $group->fill($data);
        $group->save();

        return $this->jsonSuccessResponse(null, 200, "Group info succesfully updated");
    }

    /**
     * @group Groups
     * 
     * Delete Group
     * 
     * Delete group information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (is_null($group)) {
			return $this->jsonErrorResourceNotFound();
        }

        $group->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }
}
