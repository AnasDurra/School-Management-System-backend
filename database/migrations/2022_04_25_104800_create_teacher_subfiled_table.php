<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherSubfiledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_subfield', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->references('user_id')->on('teachers')->cascadeOnDelete();
            $table->foreignId('subfield_id')->constrained('subfields')->cascadeOnDelete();
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
        Schema::dropIfExists('teacher_subfield');
    }
}
