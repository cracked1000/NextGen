<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First create the users table if it doesn't exist
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['customer', 'seller', 'admin'])->default('customer');
                $table->string('address')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('optional_phone_number')->nullable();
                $table->text('description')->nullable(); // For sellers
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        // Then update the second_hand_parts table
        Schema::table('second_hand_parts', function (Blueprint $table) {
            if (!Schema::hasColumn('second_hand_parts', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('id');
            }
            
            $table->enum('status', ['Pending', 'Available', 'Sold', 'Declined'])->default('Pending')->change();
        });

        // Add the foreign key constraint separately only if both tables exist
        if (Schema::hasTable('users') && Schema::hasTable('second_hand_parts') && 
            Schema::hasColumn('second_hand_parts', 'seller_id')) {
            Schema::table('second_hand_parts', function (Blueprint $table) {
                $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('second_hand_parts', function (Blueprint $table) {
            if (Schema::hasColumn('second_hand_parts', 'seller_id')) {
                if (Schema::hasTable('users')) {
                    // Only try to drop the foreign key if the users table exists
                    try {
                        $table->dropForeign(['seller_id']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, continue anyway
                    }
                }
                $table->dropColumn('seller_id');
            }
            
            if (Schema::hasColumn('second_hand_parts', 'status')) {
                $table->enum('status', ['Available', 'Sold'])->default('Available')->change();
            }
        });
    }
};