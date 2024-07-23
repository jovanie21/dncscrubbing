<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDncUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_dnc_uploads', function (Blueprint $table) {
            $table->id();
            $table->String('upload_name');
            $table->String('file_path');
            $table->Integer('admin_id');
            $table->tinyInteger('is_action')->comment('1 for unprocessed 2 for processed');
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
        Schema::dropIfExists('admin_dnc_uploads');
    }
}
