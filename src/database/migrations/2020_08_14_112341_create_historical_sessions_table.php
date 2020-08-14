<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricalSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id');
            $table->string('state');
            $table->longText('payload')->nullable();
            $table->string('locale')->nullable();
            $table->string('msisdn');
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
        Schema::dropIfExists('historical_sessions');
    }
}
