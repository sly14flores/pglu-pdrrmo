<?php

namespace App\Http\Resources\Incidents;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class IncidentsListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'incident_number' => $this->incident_number,
            'incident_type_id' => $this->incident_type_id,
            'incident_type' => (is_null($this->incidentType))?null:$this->incidentType->name,            
            'response_type_id' => $this->response_type_id,
            'response_type' => (is_null($this->responseType))?null:$this->responseType->name,
            'incident_date' => Carbon::parse($this->incident_date)->format('F j, Y'),
            'incident_time' => Carbon::parse($this->incident_time)->format('H:i A'),
            'communication_mode_id' => $this->communication_mode_id,
            'communication_mode' => (is_null($this->communicationMode))?null:$this->communicationMode->name,
            'requestor_name' => $this->requestor_name,
            'number_of_casualty' => $this->number_of_casualty,
            'incident_status' => $this->incident_status,
            'landmark' => $this->landmark,
            'street_purok_sitio' => $this->street_purok_sitio,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'province' => $this->province,
            'region' => $this->region,
            'what_happened' => $this->what_happened,
            'agencies' => $this->agencies()->get(),
            'facilities' => $this->facilities()->get(),
            'staffs' => $this->staffs()->get(),
            'agents' => $this->agents()->get(),
            'vehicles' => $this->vehicles()->get(),
            'created_at' => Carbon::parse($this->created_at)->format('F j, Y'),
        ];
    }
}
