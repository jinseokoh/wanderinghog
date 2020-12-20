<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->string('token')->nullable()->comment('단말기 토큰');
            $table->string('arn')->nullable()->comment('SNS application endpoint');
            $table->string('platform', 32)->nullable()->comment('iOS or Android');
            $table->timestamps();

            $table->unique(['user_id', 'token']);

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
        Schema::table('device_tokens', function (Blueprint $table) {
            $table->dropUnique('device_tokens_user_id_token_unique');
            $table->dropForeign('device_tokens_user_id_foreign');
        });

        Schema::dropIfExists('device_tokens');
    }
}
