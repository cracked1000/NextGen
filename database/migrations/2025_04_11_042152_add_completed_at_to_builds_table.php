<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedAtToBuildsTable extends Migration
{
    public function up()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('total_price');
        });
    }

    public function down()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
}