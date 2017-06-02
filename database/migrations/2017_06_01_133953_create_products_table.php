<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
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
            $table->string('name');
            $table->date('purchase_date')->nullable();
            $table->integer('purchase_price')->nullable();
            $table->string('purchase_place')->nullable();
            $table->date('sale_date')->nullable();
            $table->integer('sale_price')->nullable();
            $table->string('sale_place')->nullable();
            $table->integer('category_id')->nullable();
            $table->date('exhibition_date')->nullable();
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
        Schema::dropIfExists('products');
    }
}
