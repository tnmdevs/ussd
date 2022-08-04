<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_numbers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('msisdn')->index();
            $table->foreignId('session_id')->nullable()->constrained('sessions')->cascadeOnDelete();
            $table->string('ussd_session');
            $table->string('last_screen');
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
        Schema::dropIfExists('session_numbers');
    }
};
