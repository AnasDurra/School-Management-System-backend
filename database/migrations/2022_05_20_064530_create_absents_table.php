<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsentsTable extends Migration
{

    public function up()
    {
        Schema::create('absents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('user_id')->on('students')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('is_justified')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absents');
    }
}
