<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('user_id');
            $table->string('branch');
            $table->string('account_number');
            $table->enum('account_type',['Current','Saving','Credit','Joint']);
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->foreign('bank_id')
                ->references('id'
                )->on('banks');

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
