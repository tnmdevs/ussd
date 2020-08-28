<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionIdToSessionNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_numbers', function (Blueprint $table) {
            $table->foreignId('session_id')->after('msisdn')->nullable()
                ->constrained('sessions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_numbers', function (Blueprint $table) {
            $table->dropColumn('session_id');
        });
    }
}
