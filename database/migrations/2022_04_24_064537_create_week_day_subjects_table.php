<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekDaySubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_day_subjects', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->foreignId('subject_id')->nullable();
            $table->foreignId('week_day_id')->constrained('week_days')->cascadeOnDelete();
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
        Schema::dropIfExists('week_day_subjects');
    }
}
