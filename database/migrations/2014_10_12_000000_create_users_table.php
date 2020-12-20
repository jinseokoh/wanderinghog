<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
$table->string('email', 64)->unique()->comment('이메일주소');
$table->string('username', 24)->unique()->nullable()->comment('아이디');
$table->string('phone', 24)->unique()->nullable()->comment('전화번호');
$table->string('name', 24)->nullable()->comment('실명');
$table->string('password')->nullable()->comment('비밀번호');
$table->string('gender', 1)->nullable()->comment('성별');
$table->date('dob')->nullable()->comment('생년월일');
$table->string('locale', 8)->nullable()->comment('기본언어');
$table->string('avatar')->nullable()->comment('아바타주소');
$table->string('uuid', 36)->nullable()->comment('uuid v4 from mobile device');
$table->string('device', 64)->nullable()->comment('device');
$table->boolean('is_active')->default(false)->comment('활성화 여부');
$table->timestamp('email_verified_at')->nullable()->comment('이메일 인증시각');
$table->timestamp('phone_verified_at')->nullable()->comment('전화 인증시각');
$table->timestamp('profession_verified_at')->nullable()->comment('직업 인증시각');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
