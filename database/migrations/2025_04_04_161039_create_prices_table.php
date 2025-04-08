<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->string('purchase_url');
            $table->foreignId('retailer_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('priceable_id');
            $table->string('priceable_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};