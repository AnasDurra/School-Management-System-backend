<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomTeacherSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_teacher_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacherSubject_id')->constrained('teacher_subject')->cascadeOnDelete();
            $table->foreignId('classroom_id')->references('id')->on('classrooms')->cascadeOnDelete();
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
        Schema::dropIfExists('classroom_teacher_subject');
    }
}
