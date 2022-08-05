<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminResponsibilitiesTable extends Migration
{
    public function up()
    {
        Schema::create('admin_responsibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->references('user_id')->on('admins')->cascadeOnDelete();
            $table->foreignId('responsibility_id')->references('id')->on('responsibilities')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('admin_responsibilities');
    }
}
