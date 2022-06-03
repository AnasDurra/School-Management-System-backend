<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->foreignId('parent_id')->references('user_id')->on('paarents')->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('admin_id')->nullable()->references('user_id')->on('admins')->nullOnDelete();
            $table->boolean('seen')->nullable()->default(false);
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
        Schema::dropIfExists('complaints');
    }
}
