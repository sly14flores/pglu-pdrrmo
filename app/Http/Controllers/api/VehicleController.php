<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
     * @group Vehicles
     *
     * Vehicles list
     *
     * Paginated list of vehicles
     *
     * @authenticated 
     */
    public function index()
    {

        $rows = Vehicle::latest()->paginate(10);

        $data = new VehicleResourceCollection($rows);

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

    private function rules($isNew,$model=null)
    {
        $rules = [
            'name' => 'required|string|unique:vehicles',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('vehicles')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Vehicles
     * 
     * Add new vehicle
     * 
     * Vehicle input
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

        $model = new Vehicle;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Vehicle succesfully added");
    }

    /**
     * @group Vehicles
     *
     * Get vehicle
     * 
     * Show Vehicle Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = Vehicle::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new VehicleResource($model);

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
     * @group Vehicles
     * 
     * Edit vehicle
     * 
     * Update vehicle information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = Vehicle::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$model));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Vehicle info succesfully updated");
    }

    /**
     * @group Vehicles
     * 
     * Delete Vehicle
     * 
     * Delete vehicle information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = Vehicle::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Vehicles
     * 
     * Batch Delete Vehicles
     * 
     * Delete vehicles information by IDs
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

        Vehicle::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
