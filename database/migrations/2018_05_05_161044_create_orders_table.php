<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nsn')->unique();
            $table->foreign('nsn')->references('nsn')->on('it_imports');
            $table->unsignedInteger('presells_id');
            $table->foreign('presell_id')->references('id')->on('presells');
            $table->unsignedInteger('order_board_id');
            $table->foreign('order_board_id')->references('id')->on('order_boards');
            $table->unsignedInteger('protective_cover_id');
            $table->foreign('protective_cover_id')->references('id')->on('protective_covers');
            $table->unsignedInteger('height_requirement_id');
            $table->foreign('height_requirement_id')->references('id')->on('height_requirements');
            $table->string('delivery_note')->nullable();
            $table->string('notes')->nullable();
            $table->date('requested_enclosure_delivery_date');
            $table->date('ship_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
