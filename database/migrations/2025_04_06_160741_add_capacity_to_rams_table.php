<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityToRamsTable extends Migration
{
    public function up()
    {
        Schema::table('rams', function (Blueprint $table) {
            $table->integer('capacity')->nullable()->after('ram_speed'); // e.g., 16 (in GB)
        });
    }

    public function down()
    {
        Schema::table('rams', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
}