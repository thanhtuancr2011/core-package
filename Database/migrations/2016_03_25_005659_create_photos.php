<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('folder', 100);
            $table->string('name', 150);
            $table->string('stored_file_name', 150);
            $table->string('size', 50);
            $table->string('uniId', 50);
            $table->integer('imageable_id')->unsigned();
            $table->string('imageable_type', 150);
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
        Schema::drop('photos');
    }
}
