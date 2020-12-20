<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index()->comment('질문 foreign 키');
            $table->unsignedBigInteger('user_id')->index()->comment('사용자 foreign 키');
            $table->string('body')->nullable()->comment('답변내용');
            $table->unsignedTinyInteger('count_likes')->default(0)->comment('호감 갯수');
            $table->unsignedTinyInteger('count_flags')->default(0)->comment('사용자 부적절 신고');
            $table->date('taken_at')->nullable()->comment('첨부사진이 있는 경우, 해당 사진의 촬영날짜');
            $table->timestamps();

            $table->unique(['question_id', 'user_id']);
            // foreign key constraints
            $table->foreign('question_id')
                ->references('id')
                ->on('questions');
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
        Schema::dropIfExists('answers');
    }
}
