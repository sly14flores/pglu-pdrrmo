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
        // $now = Carbon::now()->format('Y-m-d');

        // $medical = [
        //     'id' => '',
        //     'noi_moi' => '',
        //     'is_covid19' => false,
        //     'patient_name' => '',
        //     'age' => '',
        //     'gender' => '',
        //     'address' => '',
        //     'region' => '',
        //     'region_name' => '',
        //     'province' => '',
        //     'city_municipality' => '',
        //     'barangay' => '',
        //     'street_purok_sitio' => '',
        //     'facility_referral' => false,
        //     'transport_type_id' => '',
        //     'transport_type' => '',
        //     'facility_id' => '',
        //     // 'complaints' => [],
        //     // 'interventions' => [],
        //     'complaints' => '',
        //     'interventions' => '',
        //     'medics' => [],
        // ];

        $medicals = $this->medicals()->get();

        if ($medicals->count()) {
            $medicals = $medicals->transform(function($medical, $key) {

                $medical_street_purok_sitio = ($medical->street_purok_sitio!=null)?$medical->street_purok_sitio.' ':'';

                $value = [
                    'id' => $medical->id,
                    'noi_moi' => $medical->noi_moi,
                    'is_covid19' => $medical->is_covid19,
                    'patient_name' => $medical->patient_name,
                    'age' => $medical->age,
                    'gender' => $medical->gender,
                    'address' => "{$medical_street_purok_sitio}{$medical->medicalBarangay->barangay_description}, {$medical->medicalCity->city_municipality_description}, {$medical->medicalProvince->province_description}",
                    'region' => $medical->region,
                    'region_name' => $medical->medicalRegion->region_description,
                    'province' => $medical->province,
                    'city_municipality' => $medical->city_municipality,
                    'barangay' => $medical->barangay,
                    'street_purok_sitio' => $medical->street_purok_sitio,
                    'facility_referral' => $medical->facility_referral,
                    'transport_type_id' => $medical->transport_type_id,
                    'transport_type' => $medical->transportType->name,
                    'facility_id' => $medical->facility_id ?? '',
                    'facility' => $medical->facility->name ?? '',
                    'complaints' => $medical->complaints,
                    'interventions' => $medical->interventions,
                    'medics' => $medical->medics()->get()->pluck('id'),
                    '_medics' => $medical->medics()->get(['firstname','lastname']),
                ];

                return $value;
            });
        }

        $incident_street_purok_sitio = ($this->street_purok_sitio!=null)?$this->street_purok_sitio.' ':'';

        $vehicles = $this->vehicles()->get();

        if ($vehicles->count()) {
            $vehicles = $vehicles->transform(function($vehicle, $key) {

                $value = [
                    'vehicle_id' => $vehicle->id,
                    'vehicle_name' => $vehicle->name,
                    'time_depart_from_base' => ($vehicle->pivot->time_depart_from_base!=null)?Carbon::parse($vehicle->pivot->time_depart_from_base)->format('h:i A'):null,
                    'time_arrive_at_incident_site' => ($vehicle->pivot->time_arrive_at_incident_site!=null)?Carbon::parse($vehicle->pivot->time_arrive_at_incident_site)->format('h:i A'):null,
                    'time_depart_from_incident_site' => ($vehicle->pivot->time_depart_from_incident_site!=null)?Carbon::parse($vehicle->pivot->time_depart_from_incident_site)->format('h:i A'):null,
                    'time_arrive_at_facility' => ($vehicle->pivot->time_arrive_at_facility!=null)?Carbon::parse($vehicle->pivot->time_arrive_at_facility)->format('h:i A'):null,
                    'time_depart_from_facility' => ($vehicle->pivot->time_depart_from_facility!=null)?Carbon::parse($vehicle->pivot->time_depart_from_facility)->format('h:i A'):null,
                    'time_arrive_at_base' => ($vehicle->pivot->time_arrive_at_base!=null)?Carbon::parse($vehicle->pivot->time_arrive_at_base)->format('h:i A'):null,
                    'starting_mileage' => $vehicle->pivot->starting_mileage,
                    'incident_site_mileage' => $vehicle->pivot->incident_site_mileage,
                    'ending_mileage' => $vehicle->pivot->ending_mileage,
                ];

                return $value;
            });
        }

        return [
            'id' => $this->id,
            'incident_type_id' => $this->incident_type_id,
            'incident_type' => $this->incidentType->name ?? '',
            'response_type_id' => $this->response_type_id,
            'response_type' => $this->responseType->name,
            'incident_date' => $this->incident_date,
            'incident_fdate' => Carbon::parse($this->incident_date)->format('F j, Y'),
            'incident_time' => $this->incident_time,
            'incident_ftime' => Carbon::parse($this->incident_time)->format('h:i A'),
            'communication_mode_id' => $this->communication_mode_id,
            'communication_mode' => $this->communicationMode->name,
            'requestor_name' => $this->requestor_name,
            'number_of_casualty' => $this->number_of_casualty,
            'incident_status' => $this->incident_status,
            'landmark' => $this->landmark,
            'address' => "{$incident_street_purok_sitio}{$this->incidentBarangay->barangay_description}, {$this->incidentCity->city_municipality_description}, {$this->incidentProvince->province_description}",
            'street_purok_sitio' => $this->street_purok_sitio,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'province' => $this->province,
            'region' => $this->region,
            'region_name' => $this->incidentRegion->region_description,
            'what_happened' => $this->what_happened,
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
            'medicals' => $medicals,
            'vehicles' => $vehicles,
            'delete_medicals' => [],
            'created_at' => Carbon::parse($this->created_at)->format('F j, Y'),
        ];
    }
}
