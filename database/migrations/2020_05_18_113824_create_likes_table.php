<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->morphs('likable');
            $table->softDeletes();
            $table->timestamps();

            // unique key constraints
            $table->unique(['likable_id', 'likable_type', 'user_id']);
            // foreign key constraints
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('like_counters', function (Blueprint $table) {
            $table->id();
            $table->morphs('likable');
            $table->integer('count')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();

            // unique key constraints
            $table->unique(['likable_id', 'likable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('like_counters');
    }
}
