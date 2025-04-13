<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeComponentIdsNullableInBuildsTable extends Migration
{
    public function up()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->unsignedBigInteger('cpu_id')->nullable()->change();
            $table->unsignedBigInteger('motherboard_id')->nullable()->change();
            $table->unsignedBigInteger('gpu_id')->nullable()->change();
            $table->unsignedBigInteger('power_supply_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->unsignedBigInteger('cpu_id')->nullable(false)->change();
            $table->unsignedBigInteger('motherboard_id')->nullable(false)->change();
            $table->unsignedBigInteger('gpu_id')->nullable(false)->change();
            $table->unsignedBigInteger('power_supply_id')->nullable(false)->change();
        });
    }
}