<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\Vehicle;
use App\Http\Resources\Vehicles\VehicleResource;
use App\Http\Resources\Vehicles\VehicleResourceCollection;

class VehicleController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->Agencies
     *
     * Agencies list
     *
     * Paginated list of agencies
     *
     * @authenticated 
     */
    public function index()
    {

        $rows = Agency::latest()->paginate(10);

        $data = new AgencyResourceCollection($rows);

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

    private function rules($isNew,$communication_mode=null)
    {
        $rules = [
            'name' => 'required|string',
            // 'description' => 'string',
        ];

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->Agencies
     * 
     * Add new agency
     * 
     * Agency input
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
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        $model = new Agency;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Agency succesfully added");
    }

    /**
     * @group Maintenance->Agencies
     *
     * Get agency
     * 
     * Show Agency Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = Agency::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new AgencyResource($model);

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
     * @group Maintenance->Agencies
     * 
     * Edit agency
     * 
     * Update agency information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = Agency::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$model));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Agency info succesfully updated");
    }

    /**
     * @group Maintenance->Agencies
     * 
     * Delete Agency
     * 
     * Delete agency information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = Agency::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->Agencies
     * 
     * Batch Delete Agencies
     * 
     * Delete agencies information by IDs
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
            'ids.required' => 'No IDs provided'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        Agency::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
