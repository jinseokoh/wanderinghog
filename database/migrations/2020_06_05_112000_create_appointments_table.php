<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->unsignedBigInteger('venue_id')->index()->comment('장소 foreign 키');
            $table->unsignedTinyInteger('theme_type')->default(1)->comment('모임주제 type');
            $table->string('title', 128)->comment('모임 제목');
            $table->string('description')->comment('why you should join us?');
            $table->json('questions')->nullable()->comment('questions to ask');
            $table->unsignedInteger('estimate')->nullable()->comment('1인참가 예상비용');
            $table->unsignedTinyInteger('age')->nullable()->comment('사용자 나이');

            $table->unsignedInteger('view_count')->default(0)->comment('조회수');
            $table->unsignedInteger('like_count')->default(0)->comment('likes count');
            $table->boolean('is_closed')->default(false)->comment('참가가능 여부');
            $table->boolean('is_active')->default(false)->comment('활성 여부');

            $table->date('expired_at')->nullable()->comment('만료일');
            $table->softDeletes();
            $table->timestamps();

            // foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            // foreign key constraints
            $table->foreign('venue_id')
                ->references('id')
                ->on('venues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
