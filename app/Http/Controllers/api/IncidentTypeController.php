<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\IncidentType;
use App\Http\Resources\Maintenance\IncidentTypeResource;
use App\Http\Resources\Maintenance\IncidentTypeResourceCollection;

class IncidentTypeController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->IncidentTypes
     *
     * Incident Types list
     *
     * Paginated list of incident types
     *
     * @authenticated 
     */
    public function index()
    {

        $results = IncidentType::latest()->paginate(10);

        $data = new IncidentTypeResourceCollection($results);

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
            'name' => 'required|string|unique:incident_types',
            'short_name' => 'string|unique:incident_types',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('incident_types')->ignore($model);
            $rules['short_name'] = Rule::unique('incident_types')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->IncidentTypes
     * 
     * Add new incident type
     * 
     * Incident type input
     *
     * @bodyParam name string required
     * @bodyParam description string
     * @bodyParam short_name string
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

        $model = new IncidentType;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Incident type succesfully added");
    }

    /**
     * @group Maintenance->IncidentTypes
     *
     * Get incident type
     * 
     * Show Incident Type Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = IncidentType::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new IncidentTypeResource($model);

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
     * @group Maintenance->IncidentTypes
     * 
     * Edit incident type
     * 
     * Update incident type information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * @bodyParam short_name string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = IncidentType::find($id);

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

        return $this->jsonSuccessResponse(null, 200, "Incident type info succesfully updated");
    }

    /**
     * @group Maintenance->IncidentTypes
     * 
     * Delete Incident Type
     * 
     * Delete incident type information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = IncidentType::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->IncidentTypes
     * 
     * Batch Delete Incident Types
     * 
     * Delete incident types information by IDs
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

        IncidentType::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
