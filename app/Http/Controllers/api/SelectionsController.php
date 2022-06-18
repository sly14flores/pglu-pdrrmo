<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CommunicationMode;
use App\Models\ResponseType;
use App\Models\IncidentType;
use App\Models\Group;
use App\Models\User;
use App\Models\Agency;
use App\Models\Facility;
use App\Models\Vehicle;
use App\Models\TransportType;
use App\Models\Intervention;
use App\Models\Complaint;

use App\Traits\Messages;
use App\Traits\Dumper;

class SelectionsController extends Controller
{
    use Messages, Dumper;

	public function __construct()
	{
		$this->middleware(['auth:api']);
    }

    /**
     * @group Selections
     * 
     * Communication modes selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function communicationModes()
    {
        $data = CommunicationMode::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Response types selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function responseTypes()
    {
        $data = ResponseType::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Incident types selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function incidentTypes()
    {
        $data = IncidentType::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Groups selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function Groups()
    {
        $data = Group::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Users selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function users()
    {
        $data = User::all();

        $data = $data->map(function($d) {
            return [
                'id' => $d->id,
                'name' => "{$d->firstname} {$d->lastname}"
            ];
        });

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Agencies selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function agencies()
    {
        $data = Agency::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Facilities selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function facilities()
    {
        $data = Facility::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Vehicles selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function vehicles()
    {
        $data = Vehicle::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Transport types selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function transportTypes()
    {
        $data = TransportType::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Interventions selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function interventions()
    {
        $data = Intervention::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Selections
     * 
     * Complaints selection
     * 
     * For dropdown or select data
     * 
     * @authenticated
     */
    public function complaints()
    {
        $data = Complaint::all(['id','name']);

        return $this->jsonSuccessResponse($data, 200);
    }

}
