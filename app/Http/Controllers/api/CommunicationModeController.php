<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\CommunicationMode;
use App\Http\Resources\Maintenance\CommunicationModeResource;
use App\Http\Resources\Maintenance\CommunicationModeResourceCollection;

class CommunicationModeController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Maintenance->CommunicationModes
     *
     * Communication Modes list
     *
     * Paginated list of communication modes
     *
     * @authenticated 
     */
    public function index()
    {

        $communication_modes = CommunicationMode::latest()->paginate(10);

        $data = new CommunicationModeResourceCollection($communication_modes);

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
            'description' => 'string',
            'short_name' => 'string',
        ];

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Maintenance->CommunicationModes
     * 
     * Add new communication mode
     * 
     * Communication mode input
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
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();

        $communication_mode = new CommunicationMode;
        $communication_mode->fill($data);
        $communication_mode->save();

        return $this->jsonSuccessResponse(null, 200, "Communication mode succesfully added");
    }

    /**
     * @group Maintenance->CommunicationModes
     *
     * Get communication mode
     * 
     * Show Communication Mode Information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $communication_mode = CommunicationMode::find($id);

        if (is_null($communication_mode)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new CommunicationModeResource($communication_mode);

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
     * @group Maintenance->CommunicationModes
     * 
     * Edit communication mode
     * 
     * Update communication mode information
     *
     * @bodyParam name string required
     * @bodyParam description string
     * 
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $communication_mode = CommunicationMode::find($id);

        if (is_null($communication_mode)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$communication_mode));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();
        $communication_mode->fill($data);
        $communication_mode->save();

        return $this->jsonSuccessResponse(null, 200, "Communication mode info succesfully updated");
    }

    /**
     * @group Maintenance->CommunicationModes
     * 
     * Delete Communication Mode
     * 
     * Delete communication mode information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $communication_mode = CommunicationMode::find($id);

        if (is_null($communication_mode)) {
			return $this->jsonErrorResourceNotFound();
        }

        $communication_mode->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Maintenance->CommunicationModes
     * 
     * Batch Delete Communication Modes
     * 
     * Delete communication modes information by IDs
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
            'ids.required' => 'No groups IDs provided'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }

        $data = $validator->valid();

        Group::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
