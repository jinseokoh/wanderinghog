<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKakaoUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kakao_user', function (Blueprint $table) {
            $table->unsignedBigInteger('kakao_id')->index()->comment('카톡친구 foreign 키');
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');

            // foreign key constraints
            $table->foreign('kakao_id')->references('id')->on('kakaos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kakao_user');
    }
}
