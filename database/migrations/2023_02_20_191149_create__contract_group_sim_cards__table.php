<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractGroupSimCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_group_sim_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('group_sim_card_id');

            $table->foreign('contract_id')->references('id')->on('contracts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_sim_card_id')->references('id')->on('group_sim_cards')
                ->onUpdate('cascade')->onDelete('cascade');

            // уникальные значения связной таблицы
            $table->primary(['contract_id', 'group_sim_card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_group_sim_cards');
    }
}
