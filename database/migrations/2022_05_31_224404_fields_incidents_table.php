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
            $table->string('province')->nullable()->after('city_municipality');
            $table->string('region')->nullable()->after('province');
            $table->string('street_purok_sitio')->nullable()->after('place_of_incident');
            $table->renameColumn('place_of_incident', 'landmark');
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
