<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractSimCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_sim_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('sim_card_id');

            $table->foreign('contract_id')->references('id')->on('contracts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sim_card_id')->references('id')->on('sim_cards')
                ->onUpdate('cascade')->onDelete('cascade');

            // уникальные значения связной таблицы
            $table->primary(['contract_id', 'sim_card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_sim_cards');
    }
}
