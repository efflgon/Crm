<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->references('id')->on('leads');
            $table->enum('status', ['new', 'sent', 'sending_error', 'deleted']);
            $table->date('date_send')->nullable();
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
        Schema::dropIfExists('leads_status');
    }
}
