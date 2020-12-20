<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKakaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kakaos', function (Blueprint $table) {
            $table->id();
            $table->string('kakao_id')->unique()->comment('카카오계정 아이디');
            $table->string('name');
            $table->string('avatar')->nullable()->comment('아바타');
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
        Schema::dropIfExists('kakaos');
    }
}
