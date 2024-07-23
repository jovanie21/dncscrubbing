<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDncExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnc_exports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->json('paths')->nullable();
            $table->string('status')->default('processing')->nullable();
            $table->integer('active_count')->default(0)->nullable();
            $table->integer('inactive_count')->default(0)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dnc_exports');
    }
}
