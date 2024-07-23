<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempScrubUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_scrub_users', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->Integer('scrub_upload_id');
            $table->String('phone_no');
            $table->Integer('user_dnc_list_id');
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
        Schema::dropIfExists('temp_scrub_users');
    }
}
