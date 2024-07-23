<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_uploads', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->String('upload_name');
            $table->Text('file_path');
            $table->TinyInteger('is_processed')->comment('1 for unprocessed 2 for processed');
            $table->TinyInteger('is_deleted')->comment('1 for unprocessed 2 for processed');
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
        Schema::dropIfExists('user_uploads');
    }
}
