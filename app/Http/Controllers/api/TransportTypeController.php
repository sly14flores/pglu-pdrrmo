<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\TransportType;
use App\Http\Resources\Maintenance\TransportTypeResource;
use App\Http\Resources\Maintenance\TransportTypeResourceCollection;

class TransportTypeController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->Transport Types
     *
     * Transport type list
     *
     * Paginated list of transport type
     *
     * @authenticated 
     */
    public function index()
    {

        $results = TransportType::latest()->paginate(10);

        $data = new TransportTypeResourceCollection($results);

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
            'name' => 'required|string|unique:transport_types',
            // 'short_name' => 'string|unique:transport_types',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('transport_types')->ignore($model);
            // $rules['short_name'] = Rule::unique('transport_types')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->Transport Types
     * 
     * Add new transport type
     * 
     * Transport type input
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

        $model = new TransportType;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Transport type succesfully added");
    }

    /**
     * @group Maintenance->Transport Types
     *
     * Get transport type
     * 
     * Show Transport Type Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = TransportType::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new TransportTypeResource($model);

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
     * @group Maintenance->Transport Types
     * 
     * Edit transport type
     * 
     * Update transport type information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = TransportType::find($id);

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

        return $this->jsonSuccessResponse(null, 200, "Transport type info succesfully updated");
    }

    /**
     * @group Maintenance->Transport Types
     * 
     * Delete Transport Type
     * 
     * Delete Transport Type information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = TransportType::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->Transport Types
     * 
     * Batch Delete Transport Type
     * 
     * Delete transport type information by IDs
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

        TransportType::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
