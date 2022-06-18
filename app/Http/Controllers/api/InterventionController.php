<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\Intervention;
use App\Http\Resources\Maintenance\InterventionResource;
use App\Http\Resources\Maintenance\InterventionResourceCollection;

class InterventionController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->Interventions
     *
     * Intervention list
     *
     * Paginated list of intervention
     *
     * @authenticated 
     */
    public function index()
    {

        $results = Intervention::latest()->paginate(10);

        $data = new InterventionResourceCollection($results);

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
            'name' => 'required|string|unique:interventions',
            // 'short_name' => 'string|unique:interventions',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('interventions')->ignore($model);
            // $rules['short_name'] = Rule::unique('interventions')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->Interventions
     * 
     * Add new intervention
     * 
     * Intervention input
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

        $model = new Intervention;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse(null, 200, "Intervention succesfully added");
    }

    /**
     * @group Maintenance->Interventions
     *
     * Get intervention
     * 
     * Show Intervention Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = Intervention::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new InterventionResource($model);

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
     * @group Maintenance->Interventions
     * 
     * Edit intervention
     * 
     * Update intervention information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = Intervention::find($id);

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

        return $this->jsonSuccessResponse(null, 200, "Intervention info succesfully updated");
    }

    /**
     * @group Maintenance->Interventions
     * 
     * Delete Intervention
     * 
     * Delete Intervention information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = Intervention::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->Interventions
     * 
     * Batch Delete Intervention
     * 
     * Delete intervention information by IDs
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

        Intervention::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
