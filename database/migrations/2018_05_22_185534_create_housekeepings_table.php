<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousekeepingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('housekeepings', function (Blueprint $table) {

            //Columns
            $table->increments('id');
            $table->unsignedInteger('guest_id'); //FK
            $table->unsignedInteger('service_id');
            $table->date('order_date');
            $table->boolean('bed_sheets');
            $table->boolean('cleaning');
            $table->boolean('minibar');
            $table->boolean('blanket');
            $table->boolean('toiletries');
            $table->boolean('pillow');
            $table->enum('status', ['0', '1', '2']);
            $table->timestamps();

            //FK
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('housekeepings');
    }
}
