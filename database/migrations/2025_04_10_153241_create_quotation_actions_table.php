<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationActionsTable extends Migration
{
    public function up()
    {
        Schema::create('quotation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // e.g., 'continue_with_build'
            $table->json('build_details')->nullable(); // Store the build details as JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotation_actions');
    }
}
