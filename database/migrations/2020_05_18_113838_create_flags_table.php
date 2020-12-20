<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->morphs('flaggable');
            $table->text('body')->nullable()->comment('신고 내역');
            $table->softDeletes();
            $table->timestamps();

            // unique key constraints
            $table->unique(['flaggable_id', 'flaggable_type', 'user_id']);
            // foreign key constraints
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('flag_counters', function (Blueprint $table) {
            $table->id();
            $table->morphs('flaggable');
            $table->integer('count')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();

            // unique key constraints
            $table->unique(['flaggable_id', 'flaggable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flags');
        Schema::dropIfExists('flag_counters');
    }
}
