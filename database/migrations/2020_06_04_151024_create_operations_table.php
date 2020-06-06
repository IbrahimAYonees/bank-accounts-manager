<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('currency_id');
            $table->enum('type',['withdraw','deposit']);
            $table->unsignedDecimal('amount','15','2');
            $table->unsignedDecimal('rate','15','2');
            $table->unsignedDecimal('account_rate','15','2');
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions');

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
}
