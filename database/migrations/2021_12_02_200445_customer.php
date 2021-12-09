<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Customer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customerID');
            $table->string('email');
            $table->string('phone')->nullable(false);
            $table->string('address')->nullable(false);
            $table->foreignId('zipCode')->references('zipCode')->on('city');
            $table->tinyInteger('isActive')->nullable(false);
            $table->dateTime('createDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('modifiedDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('firstName');
            $table->string('lastName');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}

