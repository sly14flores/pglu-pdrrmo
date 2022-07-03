<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\Complaint;
use App\Http\Resources\Maintenance\ComplaintResource;
use App\Http\Resources\Maintenance\ComplaintResourceCollection;

class ComplaintController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->Complaints
     *
     * Complaint list
     *
     * Paginated list of complaint
     *
     * @authenticated 
     */
    public function index()
    {

        $results = Complaint::latest()->paginate(10);

        $data = new ComplaintResourceCollection($results);

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
            'name' => 'required|string|unique:complaints',
            // 'short_name' => 'string|unique:complaints',
            // 'description' => 'string',
        ];

        if (!$isNew) {
            $rules['name'] = Rule::unique('complaints')->ignore($model);
            // $rules['short_name'] = Rule::unique('complaints')->ignore($model);
        }

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->Complaints
     * 
     * Add new complaint
     * 
     * Complaint input
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

        $model = new Complaint;
        $model->fill($data);
        $model->save();

        return $this->jsonSuccessResponse($model, 200, "Complaint succesfully added");
    }

    /**
     * @group Maintenance->Complaints
     *
     * Get complaint
     * 
     * Show Complaint Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = Complaint::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new ComplaintResource($model);

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
     * @group Maintenance->Complaints
     * 
     * Edit complaint
     * 
     * Update complaint information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = Complaint::find($id);

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

        return $this->jsonSuccessResponse(null, 200, "Complaint info succesfully updated");
    }

    /**
     * @group Maintenance->Complaints
     * 
     * Delete Complaint
     * 
     * Delete Complaint information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = Complaint::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->Complaints
     * 
     * Batch Delete Complaint
     * 
     * Delete complaint information by IDs
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

        Complaint::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
