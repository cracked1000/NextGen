<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSellerColumnFromSecondHandPartsTable extends Migration
{
    public function up()
    {
        Schema::table('second_hand_parts', function (Blueprint $table) {
            $table->dropColumn('seller');
        });
    }

    public function down()
    {
        Schema::table('second_hand_parts', function (Blueprint $table) {
            $table->string('seller')->nullable()->after('category');
        });
    }
}