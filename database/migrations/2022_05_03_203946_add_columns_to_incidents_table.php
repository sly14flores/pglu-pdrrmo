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
        Schema::table('incidents', function (Blueprint $table) {
            $table->uuid('id')->primary()->change();
            $table->date('incident_date')->nullable()->after('id');
            $table->time('incident_time')->nullable()->after('incident_date');
            $table->uuid('communication_mode_id')->nullable()->after('incident_time');
            // $table->foreign('communication_mode_id')->references('id')->on('communication_modes')
            // ->onDelete('cascade')
            // ->onUpdate('no action');
            $table->string('requestor_name')->nullable()->after('communication_mode_id');
            $table->integer('number_of_casualty')->nullable()->after('requestor_name');
            $table->tinyInteger('incident_status')->default(0)->after('number_of_casualty');
            $table->string('address')->nullable()->after('incident_status');
            $table->longText('what_happened')->nullable()->after('address');
            $table->tinyInteger('facility_referral')->default(0)->after('what_happened');
            $table->time('time_depart_from_base')->nullable()->after('facility_referral');
            $table->time('time_arrive_at_incident_site')->nullable()->after('time_depart_from_base');
            $table->time('time_depart_from_incident_site')->nullable()->after('time_arrive_at_incident_site');
            $table->time('time_arrive_at_facility')->nullable()->after('time_depart_from_incident_site');
            $table->time('time_depart_from_facility')->nullable()->after('time_arrive_at_facility');
            $table->time('time_arrive_at_base')->nullable()->after('time_depart_from_facility');
            $table->integer('starting_mileage')->nullable()->after('time_arrive_at_base');
            $table->integer('incident_site_mileage')->nullable()->after('starting_mileage');
            $table->integer('ending_mileage')->nullable()->after('incident_site_mileage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incidents', function (Blueprint $table) {
            //
        });
    }
};
