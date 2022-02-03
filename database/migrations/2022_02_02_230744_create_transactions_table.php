<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // debited account
            $table->unsignedBigInteger('account_frm');
            $table->foreign('account_frm')->references('id')->on('accounts')->onDelete('cascade');

            // credited account
            $table->unsignedBigInteger('account_to');
            $table->foreign('account_to')->references('id')->on('accounts')->onDelete('cascade');

            $table->double('amount');
            
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
