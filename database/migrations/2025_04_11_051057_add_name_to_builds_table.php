<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToBuildsTable extends Migration
{
    public function up()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}