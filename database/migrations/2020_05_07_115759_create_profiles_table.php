<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->unsignedTinyInteger('profession_type')->nullable()->comment('직업 type');
            $table->string('profession', 64)->nullable()->comment('수동입력값 profession_verified_at 이 설정된 것이면 인증됨.');
            $table->unsignedTinyInteger('height')->default(0)->comment('키');
            $table->unsignedTinyInteger('vehicle')->default(0)->comment('차량보유자');
            $table->unsignedTinyInteger('vegan')->default(0)->comment('채식주의자');
            $table->unsignedTinyInteger('smoking')->default(1)->comment('흡연. see comments in model profile');
            $table->unsignedTinyInteger('drinking')->default(1)->comment('음주. see comments in model profile');
            $table->decimal('latitude', 10, 8)->nullable()->comment('위도');
            $table->decimal('longitude', 11, 8)->nullable()->comment('경도');

            $table->text('intro')->nullable()->comment('소개글');

            $table->unsignedSmallInteger('level')->default(0)->comment('회원 매력지수/등급');
            $table->unsignedSmallInteger('limit')->default(0)->comment('최대 허용치');
            $table->unsignedSmallInteger('coins')->default(0)->comment('현재 보유 코인 갯수');

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
        Schema::dropIfExists('profiles');
    }
}
