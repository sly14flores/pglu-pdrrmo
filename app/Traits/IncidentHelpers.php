<?php

namespace App\Traits;

use App\Models\Incident;

use Carbon\Carbon;

trait IncidentHelpers
{

  public function incidentNumber()
  {

    $now = Carbon::now();
    $year = $now->year;
    $month = str_pad($now->month,2,"0",STR_PAD_LEFT);
    $i = "0001";

    $incident = Incident::where('created_at', 'LIKE', "{$year}-%")->latest()->first();

    if ($incident != null) {
      $no = explode("-",$incident->incident_number);
      $i = intval($no[count($no)-1]) + 1;
      $i = str_pad($i,4,"0",STR_PAD_LEFT);
    }

    $number = "LUR-{$month}-{$year}-{$i}";

    return $number;

  }

}