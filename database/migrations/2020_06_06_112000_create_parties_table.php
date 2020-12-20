<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->index()->comment('약속 foreign 키');
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->unsignedBigInteger('friend_id')->index()->comment('친구사용자 foreign 키');
            $table->enum('user_decision', ['approved', 'denied'])->nullable()->comment('사용자 판단값');
            $table->enum('friend_decision', ['approved', 'denied'])->nullable()->comment('친구사용자 판단값');

            $table->unsignedTinyInteger('relation_type')->comment('관계 type');
            $table->json('answers')->nullable()->comment('questions to ask');

            $table->boolean('is_host')->default(false)->comment('host flag');
            $table->boolean('is_excluded')->default(false)->comment('제외 flag');
            $table->timestamps();

            $table->unique(['appointment_id', 'user_id']);

            // foreign key constraints
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments');
            // foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            // foreign key constraints
            $table->foreign('friend_id')
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
        Schema::dropIfExists('parties');
    }
}
