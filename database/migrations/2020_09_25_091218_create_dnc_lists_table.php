<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDncListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnc_lists', function (Blueprint $table) {
            $table->id();
            $table->String('phone_no');
            $table->String('federal');
            $table->String('litigator');
            $table->String('internal');
            $table->String('uploaded_by');
            $table->String('modified_by');
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
        Schema::dropIfExists('dnc_lists');
    }
}
