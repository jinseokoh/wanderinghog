<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('가입질문');
            $table->string('slug')->nullable()->comment('slug');
            $table->unsignedTinyInteger('depth')->default(1)->comment('리스트내 depth');
            // $table->boolean('is_approved')->default(true)->comment('활성화 여부');
            $table->nestedSet($table);
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
        Schema::dropIfExists('questions');
    }
}
