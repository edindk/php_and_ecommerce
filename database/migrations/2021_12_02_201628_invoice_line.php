<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceLine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoiceLines', function (Blueprint $table) {
            $table->id('invoiceLineID');
            $table->foreignId('productID')->references('productID')->on('products');
            $table->foreignId('invoiceID');
            $table->integer('quantity')->nullable(false);
            $table->decimal('subTotal')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoiceLines');
    }
}
