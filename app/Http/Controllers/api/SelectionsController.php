<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CommunicationMode;
use App\Models\ResponseType;
use App\Models\Group;

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

}
