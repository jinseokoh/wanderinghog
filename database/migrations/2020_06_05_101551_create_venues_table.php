<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();

            $table->string('place_id')->index()->comment('Google 제공 Place Id');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Google 제공 위도');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Google 제공 경도');

            $table->string('title', 128)->comment('장소명');
            $table->string('address')->nullable()->comment('장소주소');

            $table->unsignedInteger('usage_count')->default(0)->comment('사용횟수');
            $table->unsignedInteger('like_count')->default(0)->comment('추천횟수');

            $table->string('description')->nullable()->comment('기타 설명, 전화번호, etc.');
            $table->json('photo_refs')->nullable()->comment('Google 제공 Place Photos Reference');

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
        Schema::dropIfExists('venues');
    }
}
