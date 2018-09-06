<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateItExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('it_exports', function (Blueprint $table) {
//            $table->increments('id');
//            $table->foreign('nsn')->references('nsn')->on('it_imports');
//            $table->string('	coates_hardware_board_type', 25);
//            $table->string('	coates_hardware_screen_protection', 25);
//            $table->unsignedInteger('coates_hardware_number_of_screens');
//            $table->string('coates_hardware_configuration', 25);
//            $table->date('coates_order_received_date');
//            $table->date('coates_order_quote_submitted');
//            $table->date('coates_order_po_approved');
//            $table->date('coates_order_ship_date');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('it_exports');
    }
}
