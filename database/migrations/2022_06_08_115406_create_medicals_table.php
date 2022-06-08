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
        Schema::create('medicals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('incident_id');
            $table->string('noi_moi')->nullable();
            $table->tinyInteger('is_covid19')->nullable();
            $table->string('patient_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('region')->nullable();
            $table->string('province')->nullable();            
            $table->string('city_municipality')->nullable();
            $table->string('street_purok_sitio')->nullable();
            $table->string('transport')->nullable();
            $table->uuid('facility_id')->nullable();
            $table->string('complaint')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicals');
    }
};
