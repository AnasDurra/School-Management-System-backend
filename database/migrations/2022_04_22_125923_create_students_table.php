<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{

    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->foreignId('classroom_id')->nullable();
            $table->foreignId('parent_id');
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('bus_id')->nullable()->constrained('buses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
