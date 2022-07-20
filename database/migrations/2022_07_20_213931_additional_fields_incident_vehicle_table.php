<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_vehicle', function (Blueprint $table) {

            $table->time('time_depart_from_base')->after('vehicle_id')->nullable();
            $table->time('time_arrive_at_incident_site')->after('time_depart_from_base')->nullable();
            $table->time('time_depart_from_incident_site')->after('time_arrive_at_incident_site')->nullable();
            $table->time('time_arrive_at_facility')->after('time_depart_from_incident_site')->nullable();
            $table->time('time_depart_from_facility')->after('time_arrive_at_facility')->nullable();
            $table->time('time_arrive_at_base')->after('time_depart_from_facility')->nullable();
            $table->integer('starting_mileage')->after('time_arrive_at_base')->nullable();
            $table->integer('incident_site_mileage')->after('starting_mileage')->nullable();
            $table->integer('ending_mileage')->after('incident_site_mileage')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
