<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDncListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_dnc_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('phone_no');
            $table->string('federal');
            $table->string('litigator');
            $table->string('internal');
            $table->string('uploaded_by');
            $table->string('modified_by');
            $table->unsignedInteger('region_id');
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
        Schema::dropIfExists('client_dnc_lists');
    }
}
