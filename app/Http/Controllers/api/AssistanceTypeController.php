<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\AssistanceType;
use App\Http\Resources\Maintenance\AssistanceTypeResource;
use App\Http\Resources\Maintenance\AssistanceTypeResourceCollection;

class AssistanceTypeController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->AssistanceTypes
     *
     * Assistance Types list
     *
     * Paginated list of assistance types
     *
     * @authenticated 
     */
    public function index()
    {

        $assistance_types = AssistanceType::latest()->paginate(10);

        $data = new AssistanceTypeResourceCollection($assistance_types);

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
            'name' => 'required|string|unique:assistance_types',
            'short_name' => 'string|unique:assistance_types',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('assistance_types')->ignore($model);
            $rules['short_name'] = Rule::unique('assistance_types')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->AssistanceTypes
     * 
     * Add new assistance type
     * 
     * Assistance type input
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

        $assistance_type = new AssistanceType;
        $assistance_type->fill($data);
        $assistance_type->save();

        return $this->jsonSuccessResponse(null, 200, "Assistance type succesfully added");
    }

    /**
     * @group Maintenance->AssistanceTypes
     *
     * Get assistance type
     * 
     * Show Assistance Type Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $assistance_type = AssistanceType::find($id);

        if (is_null($assistance_type)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new AssistanceTypeResource($assistance_type);

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
     * @group Maintenance->AssistanceTypes
     * 
     * Edit assistance type
     * 
     * Update assistance type information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * @bodyParam short_name string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $assistance_type = AssistanceType::find($id);

        if (is_null($assistance_type)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$assistance_type));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();
        $assistance_type->fill($data);
        $assistance_type->save();

        return $this->jsonSuccessResponse(null, 200, "Assistance type info succesfully updated");
    }

    /**
     * @group Maintenance->AssistanceTypes
     * 
     * Delete Assistance Type
     * 
     * Delete assistance type information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $assistance_type = AssistanceType::find($id);

        if (is_null($assistance_type)) {
			return $this->jsonErrorResourceNotFound();
        }

        $assistance_type->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->AssistanceTypes
     * 
     * Batch Delete Assistance Types
     * 
     * Delete assistance types information by IDs
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

        AssistanceType::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
