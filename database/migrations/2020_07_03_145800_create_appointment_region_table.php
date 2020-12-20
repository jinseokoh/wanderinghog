<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_region', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->index()->comment('약속 foreign 키');
            $table->unsignedBigInteger('region_id')->index()->comment('지역 foreign 키');

            // foreign key constraints
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_region');
    }
}
