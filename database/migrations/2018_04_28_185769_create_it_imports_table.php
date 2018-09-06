<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('it_imports', function (Blueprint $table) {
            $table->unsignedInteger('nsn')->primary();
            $table->string('store_address',255);
            $table->string('store_city', 255);
            $table->string('store_state', 255);
            $table->string('store_zip', 10);
            $table->string('restaurant_drive_thru_type',25);
            $table->string('coates_hardware_board_type',25);
            $table->string('coates_hardware_screen_protection',25);
            $table->unsignedInteger('coates_hardware_number_of_screens');
            $table->string('coates_hardware_configuration',25);
            $table->date('coates_order_received_date');
            $table->date('coates_order_quote_submitted');
            $table->date('coates_order_po_approved');
            $table->date('coates_order_ship_date');
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
        Schema::dropIfExists('it_imports');
    }
}
