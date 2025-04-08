<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('orderable_id'); // Polymorphic: can be a second_hand_part or a new component
            $table->string('orderable_type'); // Polymorphic: e.g., 'App\Models\SecondHandPart', 'App\Models\Cpu'
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('country');
            $table->string('province');
            $table->string('district');
            $table->string('zipcode')->nullable();
            $table->string('payment_option');
            $table->string('stripe_payment_id')->nullable();
            $table->decimal('component_price', 10, 2);
            $table->boolean('verify_product');
            $table->decimal('verify_cost', 10, 2);
            $table->decimal('shipping_charges', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['Pending', 'Completed', 'Cancelled']);
            $table->string('shipping_address')->nullable();
            $table->enum('payment_status', ['Pending', 'Paid', 'Failed'])->default('Pending');
            $table->timestamp('order_date')->nullable();
            $table->timestamps();
        });

        // Add the foreign key constraint in a separate step, only if the users table exists
        if (Schema::hasTable('users')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};