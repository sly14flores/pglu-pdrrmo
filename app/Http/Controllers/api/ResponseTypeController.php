<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\ResponseType;
use App\Http\Resources\Maintenance\ResponseTypeResource;
use App\Http\Resources\Maintenance\ResponseTypeResourceCollection;

class ResponseTypeController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->ResponseTypes
     *
     * Response Types list
     *
     * Paginated list of response types
     *
     * @authenticated 
     */
    public function index()
    {

        $response_types = ResponseType::latest()->paginate(10);

        $data = new ResponseTypeResourceCollection($response_types);

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
            'name' => 'required|string|unique:response_types',
            'short_name' => 'string|unique:response_types',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('response_types')->ignore($model);
            $rules['short_name'] = Rule::unique('response_types')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->ResponseTypes
     * 
     * Add new response type
     * 
     * Response type input
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

        $response_type = new ResponseType;
        $response_type->fill($data);
        $response_type->save();

        return $this->jsonSuccessResponse(null, 200, "Response type succesfully added");
    }

    /**
     * @group Maintenance->ResponseTypes
     *
     * Get response type
     * 
     * Show Response Type Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $response_type = ResponseType::find($id);

        if (is_null($response_type)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new ResponseTypeResource($response_type);

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
     * @group Maintenance->ResponseTypes
     * 
     * Edit response type
     * 
     * Update response type information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * @bodyParam short_name string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $response_type = ResponseType::find($id);

        if (is_null($response_type)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$response_type));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();
        $response_type->fill($data);
        $response_type->save();

        return $this->jsonSuccessResponse(null, 200, "Response type info succesfully updated");
    }

    /**
     * @group Maintenance->ResponseTypes
     * 
     * Delete Response Type
     * 
     * Delete response type information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $response_type = ResponseType::find($id);

        if (is_null($response_type)) {
			return $this->jsonErrorResourceNotFound();
        }

        $response_type->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->ResponseTypes
     * 
     * Batch Delete Response Types
     * 
     * Delete response types information by IDs
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

        ResponseType::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
