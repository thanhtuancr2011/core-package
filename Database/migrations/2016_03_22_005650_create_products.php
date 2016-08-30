<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->text('meta_description');
            $table->longText('description');
            $table->string('keywords', 150)->nullable();
            $table->string('manufacturer', 150)->nullable();
            $table->string('origin', 150);
            $table->string('color', 150);
            $table->integer('availibility')->nullable();
            $table->string('size', 150)->nullable();
            $table->string('weight', 150)->nullable();
            $table->string('dimension', 150)->nullable();
            $table->decimal('price', 10, 0);
            $table->decimal('old_price', 10, 0)->nullable();
            $table->string('alias', 150);
            $table->string('sku', 150);
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::drop('products');
    }
}
