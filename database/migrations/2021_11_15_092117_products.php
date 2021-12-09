<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('productID');
            $table->foreignId('productCategoryID')->references('productCategoryID')->on('productCategories');
            $table->string('name')->nullable(false);
            $table->string('description', 2000)->nullable(false);
            $table->integer('partNumber')->nullable(false);
            $table->float('price')->nullable(false);
            $table->integer('inStock')->nullable(false);
            $table->tinyInteger('isActive')->nullable(false)->default(1);
            $table->string('imageFile')->nullable(true);
            $table->dateTime('createDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('modifiedDate')->default(DB::raw('CURRENT_TIMESTAMP'));
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
