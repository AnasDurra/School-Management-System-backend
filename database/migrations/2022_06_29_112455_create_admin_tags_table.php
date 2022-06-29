<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTagsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->references('user_id')->on('admins')->cascadeOnDelete();
            $table->foreignId('tag_id')->references('id')->on('tags')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('admin_tags');
    }
}
