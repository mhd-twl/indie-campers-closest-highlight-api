<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('telephone')->nullable();
            // address
            $table->string('address')->nullable();
            $table->string('house_no')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            // payment
            $table->string('account_owner')->nullable();
            $table->string('iban')->nullable();
            $table->string('paymentDataId')->nullable();
            // account
            $table->integer('last_step')->default(1);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('customers');
    }
}
