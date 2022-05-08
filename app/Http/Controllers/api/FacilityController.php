<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\Facility;
use App\Http\Resources\Maintenance\FacilityResource;
use App\Http\Resources\Maintenance\FacilityResourceCollection;

class FacilityController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->Facilities
     *
     * Facilities list
     *
     * Paginated list of facilities
     *
     * @authenticated 
     */
    public function index()
    {

        $rows = Facility::latest()->paginate(10);

        $data = new FacilityResourceCollection($rows);

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
            'name' => 'required|string|unique:facilities',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('facilities')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->Facilities
     * 
     * Add new facility
     * 
     * Facility input
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

        $model = new Facility;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Facility succesfully added");
    }

    /**
     * @group Maintenance->Facilities
     *
     * Get facility
     * 
     * Show Facility Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = Facility::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new FacilityResource($model);

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
     * @group Maintenance->Facilities
     * 
     * Edit facility
     * 
     * Update facility information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = Facility::find($id);

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

        return $this->jsonSuccessResponse(null, 200, "Facility info succesfully updated");
    }

    /**
     * @group Maintenance->Facilities
     * 
     * Delete Facility
     * 
     * Delete facility information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = Facility::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->Facilities
     * 
     * Batch Delete Facilities
     * 
     * Delete facilities information by IDs
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

        Facility::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
