<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->unsignedTinyInteger('ready')->default(1)->comment('오늘의 PICK 참여 여부');

            // $table->unsignedInteger('min_distance')->default(10)->comment('선호거리 최소값');
            // $table->unsignedInteger('max_distance')->default(10000)->comment('선호거리 최대값');
            $table->unsignedTinyInteger('min_age')->default(18)->comment('선호나이 최소값');
            $table->unsignedTinyInteger('max_age')->default(99)->comment('선호나이 최대값');
            $table->unsignedTinyInteger('min_height')->default(120)->comment('선호키 최소값');
            $table->unsignedTinyInteger('max_height')->default(220)->comment('선호키 최대값');
            $table->string('gender', 1)->default('A')->comment('선호성별');

            $table->unsignedTinyInteger('smoking')->default(1)->comment('선호흡연');
            $table->unsignedTinyInteger('drinking')->default(1)->comment('선호음주');

            $table->json('notifications')->nullable()->comment('알림수신 셋팅');

            // no direct manipulation on the following columns (기계적 설정 필요)
            $table->json('appearances')->nullable()->comment('외모 셋팅');
            $table->json('careers')->nullable()->comment('능력 셋팅 - 학교/직장');
            $table->json('interests')->nullable()->comment('관심 셋팅');
            $table->json('personalities')->nullable()->comment('성격 셋팅');

            $table->timestamps();

            // foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preferences');
    }
}
