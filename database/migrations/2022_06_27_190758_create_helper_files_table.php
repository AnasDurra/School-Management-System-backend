<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelperFilesTable extends Migration
{

    public function up()
    {
        Schema::create('helper_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutorial_id')->references('id')->on('tutorials')->cascadeOnDelete();
            $table->string('file');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('helper_files');
    }
}
