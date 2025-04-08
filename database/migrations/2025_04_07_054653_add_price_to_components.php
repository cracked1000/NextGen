<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToComponents extends Migration
{
    public function up()
    {
        Schema::table('cpus', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
        Schema::table('motherboards', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
        Schema::table('gpus', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
        Schema::table('rams', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
        Schema::table('storages', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
        Schema::table('power_supplies', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('cpus', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('motherboards', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('gpus', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('rams', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('storages', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('power_supplies', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}