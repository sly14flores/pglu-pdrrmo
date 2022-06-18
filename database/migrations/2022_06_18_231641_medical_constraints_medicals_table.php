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
        Schema::table('medical_complaint', function (Blueprint $table) {
            $table->foreign('medical_id')->references('id')->on('medicals')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('medical_intervention', function (Blueprint $table) {
            $table->foreign('medical_id')->references('id')->on('medicals')
            ->onDelete('cascade')
            ->onUpdate('no action');
        });

        Schema::table('medical_medic', function (Blueprint $table) {
            $table->foreign('medical_id')->references('id')->on('medicals')
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
