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
            $table->enum('type', ['buy', 'sell']);
            $table->integer('quantity');
            $table->date('date');
            $table->unsignedBigInteger('daily_price_id');
            $table->unsignedBigInteger('pokemon_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('daily_price_id')->references('id')->on('daily_prices');
            $table->foreign('pokemon_id')->references('id')->on('pokemons');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('transactions');
    }
}
