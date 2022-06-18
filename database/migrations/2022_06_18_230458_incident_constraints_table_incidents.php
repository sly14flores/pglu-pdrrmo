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
        Schema::table('medicals', function (Blueprint $table) {
            $table->foreign('incident_id')->references('id')->on('incidents')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('incident_agency', function (Blueprint $table) {
            $table->foreign('incident_id')->references('id')->on('incidents')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('incident_agent', function (Blueprint $table) {
            $table->foreign('incident_id')->references('id')->on('incidents')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('incident_facility', function (Blueprint $table) {
            $table->foreign('incident_id')->references('id')->on('incidents')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('incident_staff', function (Blueprint $table) {
            $table->foreign('incident_id')->references('id')->on('incidents')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('incident_vehicle', function (Blueprint $table) {
            $table->foreign('incident_id')->references('id')->on('incidents')
            ->onDelete('cascade')
            ->onUpdate('no action');
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
