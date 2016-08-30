<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->integer('sort_order')->default(1);
            $table->string('keywords', 150)->nullable();
            $table->longText('description')->nullable();
            $table->string('alias', 150);
            $table->integer('parent_id');
            $table->string('ancestor_ids');
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
        Schema::drop('categories');
    }
}
