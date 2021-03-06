<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Traits\Messages;
use App\Traits\Dumper;
use App\Traits\IncidentHelpers;

use App\Models\Incident;
use App\Http\Resources\Incidents\IncidentResource;
use App\Http\Resources\Incidents\IncidentResourceCollection;

use App\Models\Medical;

class IncidentController extends Controller
{
    use Messages, Dumper, IncidentHelpers;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Incidents
     *
     * Incidents list
     *
     * Paginated list of incidents
     *
     * @authenticated 
     */
    public function index()
    {
        $rows = Incident::latest()->paginate(10);

        $data = new IncidentResourceCollection($rows);

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
            'incident_type_id' => 'required|string',
            'response_type_id' => 'required|string',
            'incident_date' => 'required|string',
            'incident_time' => 'required|string',
            'communication_mode_id' => 'required|string',
            'requestor_name' => 'string|nullable',
            'number_of_casualty' => 'integer',
            'incident_status' => 'boolean',
            'landmark' => 'nullable|string',
            'street_purok_sitio' => 'string|nullable',
            'barangay' => 'required|string',
            'city_municipality' => 'required|string',
            'province' => 'required|string',
            'region' => 'required|string',
            'what_happened' => 'required|string',
            'agencies' => 'array',
            'facilities' => 'array',
            'staffs' => 'array',
            'agents' => 'array',
            'vehicles' => 'array',
            'has_medical' => 'boolean',
            'medicals' => 'array',
        ];

        return $rules;
    }

    private function rulesMessages($isNew)
    {
        $messages = [];

        return $messages;
    }

    private function medicalRules()
    {
        $rules = [
            'noi_moi' => ['string','required'],
            'is_covid19' => ['boolean','required'],
            'patient_name' => ['string','required'],
            'age' => ['integer','required'],
            'gender' => ['string','required'],
            'region' => ['string','required'],
            'province' => ['string','required'],
            'city_municipality' => ['string','required'],
            'barangay' => ['string','required'],
            'street_purok_sitio' => ['string','nullable'],
            'facility_referral' => 'boolean',
            'transport_type_id' => ['string','required'],
            'facility_id' => ['string','nullable'],
            'complaints' => ['string','required'],
            'interventions' => ['string','required'],
            'medics' => ['array','required'],
        ];

        return $rules;
    }

    private function medicalRulesMessages()
    {
        $messages = [];

        return $messages;
    }

    /**
     * @group Incidents
     * 
     * Add new incident
     * 
     * Incident input
     *
     * @bodyParam incident_type_id string required
     * @bodyParam response_type_id string required
     * @bodyParam incident_date date required
     * @bodyParam incident_time time required
     * @bodyParam communication_mode_id string required
     * @bodyParam requestor_name string
     * @bodyParam number_of_casualty integer
     * @bodyParam incident_status boolean
     * @bodyParam landmark string
     * @bodyParam street_purok_sitio string
     * @bodyParam barangay string required
     * @bodyParam city_municipality string required
     * @bodyParam province string required
     * @bodyParam region string required
     * @bodyParam what_happened string required
     * @bodyParam agencies string[]
     * @bodyParam facilities string[]
     * @bodyParam staffs string[]
     * @bodyParam agents string[]
     * @bodyParam vehicles object[]
     * @bodyParam vehicles[].id string
     * @bodyParam vehicles[].time_depart_from_base string
     * @bodyParam vehicles[].time_arrive_at_incident_site string
     * @bodyParam vehicles[].time_depart_from_incident_site string
     * @bodyParam vehicles[].time_arrive_at_facility string
     * @bodyParam vehicles[].time_depart_from_facility string
     * @bodyParam vehicles[].time_arrive_at_base string
     * @bodyParam vehicles[].starting_mileage string
     * @bodyParam vehicles[].incident_site_mileage string
     * @bodyParam vehicles[].ending_mileage string
     * @bodyParam medicals object[]
     * @bodyParam medicals[].noi_moi string
     * @bodyParam medicals[].is_covid19 boolean
     * @bodyParam medicals[].patient_name string
     * @bodyParam medicals[].age integer
     * @bodyParam medicals[].gender string
     * @bodyParam medicals[].region string
     * @bodyParam medicals[].province string
     * @bodyParam medicals[].city_municipality string
     * @bodyParam medicals[].barangay string
     * @bodyParam medicals[].street_purok_sitio string
     * @bodyParam medicals[].facility_referral boolean
     * @bodyParam medicals[].transport_type_id string
     * @bodyParam medicals[].facility_id string
     * @bodyParam medicals[].complaints string
     * @bodyParam medicals[].interventions string
     * @bodyParam medicals[].medics array
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

        DB::beginTransaction();

        try {

            $data['incident_time'] = Carbon::parse($data['incident_time'])->format('H:i:s');
        
            $model = new Incident;
            $model->fill($data);
            $model->incident_number = $this->incidentNumber();
            $model->save();

            if (isset($data['agencies'])) {
                $model->agencies()->sync($data['agencies']);
            }
            if (isset($data['facilities'])) {
                $model->facilities()->sync($data['facilities']);
            }
            if (isset($data['staffs'])) {
                $model->staffs()->sync($data['staffs']);
            }
            if (isset($data['agents'])) {
                $model->agents()->sync($data['agents']);
            }
            if (isset($data['vehicles'])) {
                $vehicles = $data['vehicles'];
                $syncs = [];
                foreach ($vehicles as $vehicle) {
                    $syncs[$vehicle['vehicle_id']] = [
                        'time_depart_from_base' => ($vehicle['time_depart_from_base']=="")?null:Carbon::parse($vehicle['time_depart_from_base'])->format('H:i:s'),
                        'time_arrive_at_incident_site' => ($vehicle['time_arrive_at_incident_site']=="")?null:Carbon::parse($vehicle['time_arrive_at_incident_site'])->format('H:i:s'),
                        'time_depart_from_incident_site' => ($vehicle['time_depart_from_incident_site']=="")?null:Carbon::parse($vehicle['time_depart_from_incident_site'])->format('H:i:s'),
                        'time_arrive_at_facility' => ($vehicle['time_arrive_at_facility']=="")?null:Carbon::parse($vehicle['time_arrive_at_facility'])->format('H:i:s'),
                        'time_depart_from_facility' => ($vehicle['time_depart_from_facility']=="")?null:Carbon::parse($vehicle['time_depart_from_facility'])->format('H:i:s'),
                        'time_arrive_at_base' => ($vehicle['time_arrive_at_base']=="")?null:Carbon::parse($vehicle['time_arrive_at_base'])->format('H:i:s'),
                        'starting_mileage' => $vehicle['starting_mileage'],
                        'incident_site_mileage' => $vehicle['incident_site_mileage'],
                        'ending_mileage' => $vehicle['ending_mileage'],
                    ];
                }
                $model->vehicles()->sync($syncs);
            }

            /**
             * Medical
             */
            $medicals = $request->medicals;
            if (count($medicals)) {
                foreach ($medicals as $medical) {
                    $childModel = new Medical;
                    $childValidator = Validator::make($medical, $this->medicalRules());
    
                    if ($childValidator->fails()) {
                        return $this->jsonErrorDataValidation($childValidator->errors());
                    }
    
                    $childData = $childValidator->valid();
    
                    $childModel->fill($childData);
                    $model->medicals()->save($childModel);
    
                    if (isset($childData['medics'])) {
                        $childModel->medics()->sync($childData['medics']);
                    }
                }
            }

            DB::commit();

            return $this->jsonSuccessResponse($model, 200, "Incident succesfully added");

        } catch (\Expection $e) {

            DB::rollBack();

            report($e);

			return $this->jsonFailedResponse([
                $e->getMessage()
            ], 500, 'Something went wrong.');

        }

    }

    /**
     * @group Incidents
     *
     * Get incident
     * 
     * Show incident information
     * 
     * @authenticated
     */
    public function show($id)
    {
        $model = Incident::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

		$data = new IncidentResource($model);

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
     * @group Incidents
     * 
     * Edit incident
     * 
     * Update incident information
     *
     * @bodyParam response_type_id string required
     * @bodyParam incident_date date required
     * @bodyParam incident_time time required
     * @bodyParam communication_mode_id string required
     * @bodyParam requestor_name string
     * @bodyParam number_of_casualty integer
     * @bodyParam incident_status boolean
     * @bodyParam place_of_incident string required
     * @bodyParam barangay string required
     * @bodyParam city_municipality string required
     * @bodyParam what_happened string required
     * @bodyParam facility_referral boolean
     * @bodyParam time_depart_from_base string
     * @bodyParam time_arrive_at_incident_site string
     * @bodyParam time_depart_from_incident_site string
     * @bodyParam time_arrive_at_facility string
     * @bodyParam time_depart_from_facility string
     * @bodyParam time_arrive_at_base string
     * @bodyParam starting_mileage integer
     * @bodyParam incident_site_mileage integer
     * @bodyParam ending_mileage integer
     * @bodyParam agencies string[]
     * @bodyParam facilities string[]
     * @bodyParam staffs string[]
     * @bodyParam agents string[]
     * @bodyParam vehicles object[]
     * @bodyParam vehicles[].id string
     * @bodyParam vehicles[].time_depart_from_base string
     * @bodyParam vehicles[].time_arrive_at_incident_site string
     * @bodyParam vehicles[].time_depart_from_incident_site string
     * @bodyParam vehicles[].time_arrive_at_facility string
     * @bodyParam vehicles[].time_depart_from_facility string
     * @bodyParam vehicles[].time_arrive_at_base string
     * @bodyParam vehicles[].starting_mileage string
     * @bodyParam vehicles[].incident_site_mileage string
     * @bodyParam vehicles[].ending_mileage string
     * @bodyParam medicals object[]
     * @bodyParam medicals[].noi_moi string
     * @bodyParam medicals[].is_covid19 boolean
     * @bodyParam medicals[].patient_name string
     * @bodyParam medicals[].age integer
     * @bodyParam medicals[].gender string
     * @bodyParam medicals[].region string
     * @bodyParam medicals[].province string
     * @bodyParam medicals[].city_municipality string
     * @bodyParam medicals[].barangay string
     * @bodyParam medicals[].street_purok_sitio string
     * @bodyParam medicals[].facility_referral boolean
     * @bodyParam medicals[].transport_type_id string
     * @bodyParam medicals[].facility_id string
     * @bodyParam medicals[].complaints string
     * @bodyParam medicals[].interventions string
     * @bodyParam medicals[].medics array
     *
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $model = Incident::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $validator = Validator::make($request->all(), $this->rules(false,$model));

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation();
        }

        $data = $validator->valid();

        DB::beginTransaction();

        try {

            $data['incident_time'] = Carbon::parse($data['incident_time'])->format('H:i:s');
        
            $model->fill($data);
            $model->save();

            if (isset($data['agencies'])) {
                $model->agencies()->sync($data['agencies']);
            }
            if (isset($data['facilities'])) {
                $model->facilities()->sync($data['facilities']);
            }
            if (isset($data['staffs'])) {
                $model->staffs()->sync($data['staffs']);
            }
            if (isset($data['agents'])) {
                $model->agents()->sync($data['agents']);
            }
            if (isset($data['vehicles'])) {
                $vehicles = $data['vehicles'];
                $syncs = [];
                foreach ($vehicles as $vehicle) {
                    $syncs[$vehicle['vehicle_id']] = [
                        'time_depart_from_base' => ($vehicle['time_depart_from_base']=="")?null:Carbon::parse($vehicle['time_depart_from_base'])->format('H:i:s'),
                        'time_arrive_at_incident_site' => ($vehicle['time_arrive_at_incident_site']=="")?null:Carbon::parse($vehicle['time_arrive_at_incident_site'])->format('H:i:s'),
                        'time_depart_from_incident_site' => ($vehicle['time_depart_from_incident_site']=="")?null:Carbon::parse($vehicle['time_depart_from_incident_site'])->format('H:i:s'),
                        'time_arrive_at_facility' => ($vehicle['time_arrive_at_facility']=="")?null:Carbon::parse($vehicle['time_arrive_at_facility'])->format('H:i:s'),
                        'time_depart_from_facility' => ($vehicle['time_depart_from_facility']=="")?null:Carbon::parse($vehicle['time_depart_from_facility'])->format('H:i:s'),
                        'time_arrive_at_base' => ($vehicle['time_arrive_at_base']=="")?null:Carbon::parse($vehicle['time_arrive_at_base'])->format('H:i:s'),
                        'starting_mileage' => ($vehicle['starting_mileage']==null)?0:$vehicle['starting_mileage'],
                        'incident_site_mileage' => ($vehicle['incident_site_mileage']==null)?0:$vehicle['incident_site_mileage'],
                        'ending_mileage' => ($vehicle['ending_mileage']==null)?0:$vehicle['ending_mileage'],
                    ];
                }
                $model->vehicles()->sync($syncs);
            }

            /**
             * Medical
             */
            $medicals = $request->medicals;
            if (count($medicals)) {
                foreach ($medicals as $medical) {

                    $medical_id = $medical['id'];

                    $childModel = new Medical;
                    if ($medical_id!=null) {
                        $childModel = Medical::find($medical_id);
                    }
    
                    $childValidator = Validator::make($medical, $this->medicalRules());
    
                    if ($childValidator->fails()) {
                        return $this->jsonErrorDataValidation($childValidator->errors());
                    }
    
                    $childData = $childValidator->valid();
    
                    $childModel->fill($childData);
                    $model->medicals()->save($childModel);
    
                    if (isset($childData['medics'])) {
                        $childModel->medics()->sync($childData['medics']);
                    }
                }
            }

            $delete_medicals = $request->delete_medicals;
            if (count($delete_medicals)) {
                Medical::destroy($delete_medicals);
            }

            DB::commit();

            return $this->jsonSuccessResponse(null, 200, "Incident info succesfully updated");

        } catch (\Expection $e) {

            DB::rollBack();

            report($e);

			return $this->jsonFailedResponse([
                $e->getMessage()
            ], 500, 'Something went wrong.');

        }

    }

    /**
     * @group Incidents
     * 
     * Delete Incident
     * 
     * Delete incident information
     * 
     * @authenticated
     */
    public function destroy($id)
    {
        $model = Incident::find($id);

        if (is_null($model)) {
			return $this->jsonErrorResourceNotFound();
        }

        $model->delete();

        return $this->jsonDeleteSuccessResponse(); 
    }

    /**
     * @group Incidents
     * 
     * Batch Delete Incidents
     * 
     * Delete incidents information by IDs
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

        Incident::destroy($data['ids']);

        return $this->jsonDeleteSuccessResponse(); 
    }
}
