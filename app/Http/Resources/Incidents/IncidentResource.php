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
        $now = Carbon::now()->format('Y-m-d');

        $medical = [
            'id' => '',
            'noi_moi' => '',
            'is_covid19' => false,
            'patient_name' => '',
            'age' => '',
            'gender' => '',
            'region' => '',
            'province' => '',
            'city_municipality' => '',
            'barangay' => '',
            'street_purok_sitio' => '',
            'transport_type_id' => '',
            'transport_type' => '',
            'facility_id' => '',
            // 'complaints' => [],
            // 'interventions' => [],
            'complaints' => '',
            'interventions' => '',
            'medics' => [],
        ];
        if ($this->medical != null) {
            $medical = [
                'id' => $this->medical->id,
                'noi_moi' => $this->medical->noi_moi,
                'is_covid19' => $this->medical->is_covid19,
                'patient_name' => $this->medical->patient_name,
                'age' => $this->medical->age,
                'gender' => $this->medical->gender,
                'region' => $this->medical->region,
                'province' => $this->medical->province,
                'city_municipality' => $this->medical->city_municipality,
                'barangay' => $this->medical->barangay,
                'street_purok_sitio' => $this->medical->street_purok_sitio,
                'transport_type_id' => $this->medical->transport_type_id,
                'transport_type' => $this->medical->transport->name ?? '',
                'facility_id' => $this->medical->facility_id,
                'facility' => $this->medical->facility->name ?? '',
                // 'complaints' => $this->medical->complaints()->get()->pluck('id'),
                // '_complaints' => $this->medical->complaints()->get()->pluck('name'),
                // 'interventions' => $this->medical->interventions()->get()->pluck('id'),
                // '_interventions' => $this->medical->interventions()->get()->pluck('name'),
                'complaints' => $this->medical->complaints,
                'interventions' => $this->medical->interventions,
                'medics' => $this->medical->medics()->get()->pluck('id'),
                '_medics' => $this->medical->medics()->get(['firstname','lastname']),
            ];
        }

        $street_purok_sitio = ($this->street_purok_sitio!=null)?$this->street_purok_sitio.' ':'';

        return [
            'id' => $this->id,
            'incident_type_id' => $this->incident_type_id,
            'incident_type' => $this->incidentType->name,
            'response_type_id' => $this->response_type_id,
            'response_type' => $this->responseType->name,
            'incident_date' => $this->incident_date,
            'incident_time' => $this->incident_time,
            'communication_mode_id' => $this->communication_mode_id,
            'communication_mode' => $this->communicationMode->name,
            'requestor_name' => $this->requestor_name,
            'number_of_casualty' => $this->number_of_casualty,
            'incident_status' => $this->incident_status,
            'landmark' => $this->landmark,
            'address' => "{$street_purok_sitio}{$this->incidentBarangay->barangay_description}, {$this->incidentCity->city_municipality_description}, {$this->incidentProvince->province_description}",
            'street_purok_sitio' => $this->street_purok_sitio,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'province' => $this->province,
            'region' => $this->region,
            'region_name' => $this->incidentRegion->region_description,
            'what_happened' => $this->what_happened,
            'facility_referral' => $this->facility_referral,
            'time_depart_from_base' => Carbon::parse($this->time_depart_from_base)->format('h:i A'),
            'time_arrive_at_incident_site' => Carbon::parse($this->time_arrive_at_incident_site)->format('h:i A'),
            'time_depart_from_incident_site' => Carbon::parse($this->time_depart_from_incident_site)->format('h:i A'),
            'time_arrive_at_facility' => Carbon::parse($this->time_arrive_at_facility)->format('h:i A'),
            'time_depart_from_facility' => Carbon::parse($this->time_depart_from_facility)->format('h:i A'),
            'time_arrive_at_base' => Carbon::parse($this->time_arrive_at_base)->format('h:i A'),
            'starting_mileage' => $this->starting_mileage,
            'incident_site_mileage' => $this->incident_site_mileage,
            'ending_mileage' => $this->ending_mileage,
            'agencies' => $this->agencies()->get()->pluck('id'),
            '_agencies' => $this->agencies()->get()->pluck('name'),
            'facilities' => $this->facilities()->get()->pluck('id'),
            '_facilities' => $this->facilities()->get()->pluck('name'),
            'staffs' => $this->staffs()->get()->pluck('id'),
            '_staffs' => $this->staffs()->get(['firstname','lastname']),
            'agents' => $this->agents()->get()->pluck('id'),
            '_agents' => $this->agents()->get(['firstname','lastname']),
            'vehicles' => $this->vehicles()->get()->pluck('id'),
            '_vehicles' => $this->vehicles()->get()->pluck('name'),
            'has_medical' => $this->medical != null,
            'medical' => $medical,
            'created_at' => Carbon::parse($this->created_at)->format('F j, Y'),
        ];
    }
}
