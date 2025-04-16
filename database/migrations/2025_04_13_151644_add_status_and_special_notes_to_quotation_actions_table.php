<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndSpecialNotesToQuotationActionsTable extends Migration
{
    public function up()
    {
        Schema::table('quotation_actions', function (Blueprint $table) {
            $table->string('status')->default('Build Pending')->after('source'); // Add status column with default value
            $table->text('special_notes')->nullable()->after('status'); // Add special_notes column, nullable
        });
    }

    public function down()
    {
        Schema::table('quotation_actions', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('special_notes');
        });
    }
}