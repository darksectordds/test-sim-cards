<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimCardGroupSimCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_sim_card__sim_card', function (Blueprint $table) {
            $table->unsignedBigInteger('group_sim_card_id');
            $table->unsignedBigInteger('sim_card_id');

            $table->foreign('group_sim_card_id')->references('id')->on('group_sim_cards')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sim_card_id')->references('id')->on('sim_cards')
                ->onUpdate('cascade')->onDelete('cascade');

            // уникальные значения связной таблицы
            $table->primary(['group_sim_card_id', 'sim_card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_sim_card__sim_card');
    }
}
