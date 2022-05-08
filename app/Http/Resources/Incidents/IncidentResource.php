<?php

namespace App\Http\Resources\Incidents;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class IncidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'response_type_id' => $this->response_type_id,
            'incident_date' => $this->incident_date,
            'incident_time' => $this->incident_time,
            'communication_mode_id' => $this->communication_mode_id,
            'requestor_name' => $this->requestor_name,
            'number_of_casualty' => $this->number_of_casualty,
            'incident_status' => $this->incident_status,
            'place_of_incident' => $this->place_of_incident,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'what_happened' => $this->what_happened,
            'facility_referral' => $this->facility_referral,
            'time_depart_from_base' => $this->time_depart_from_base,
            'time_arrive_at_incident_site' => $this->time_arrive_at_incident_site,
            'time_depart_from_incident_site' => $this->time_depart_from_incident_site,
            'time_arrive_at_facility' => $this->time_arrive_at_facility,
            'time_depart_from_facility' => $this->time_depart_from_facility,
            'time_arrive_at_base' => $this->time_arrive_at_base,
            'starting_mileage' => $this->starting_mileage,
            'incident_site_mileage' => $this->incident_site_mileage,
            'ending_mileage' => $this->ending_mileage,
            'agencies' => $this->agencies()->get(),
            'facilities' => $this->facilities()->get(),
            'staffs' => $this->staffs()->get(),
            'agents' => $this->agents()->get(),
            'vehicles' => $this->vehicles()->get(),
            'created_at' => Carbon::parse($this->created_at)->format('F j, Y'),
        ];
    }
}
