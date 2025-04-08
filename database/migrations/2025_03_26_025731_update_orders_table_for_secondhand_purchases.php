<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check if the part_id column doesn't already exist before adding it
            if (!Schema::hasColumn('orders', 'part_id')) {
                $table->unsignedBigInteger('part_id')->after('user_id');
                $table->foreign('part_id')->references('id')->on('second_hand_parts')->onDelete('cascade');
            }
            
            // Remove the orderable_id and orderable_type columns if they exist
            if (Schema::hasColumn('orders', 'orderable_id')) {
                $table->dropColumn('orderable_id');
            }
            
            if (Schema::hasColumn('orders', 'orderable_type')) {
                $table->dropColumn('orderable_type');
            }
            
            // The following are just modifying existing columns
            $table->string('shipping_address')->nullable()->change();
            $table->decimal('total', 10, 2)->change();
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->change();
            $table->enum('payment_status', ['Pending', 'Paid', 'Failed'])->default('Pending')->change();
            $table->timestamp('order_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key and the part_id column
            if (Schema::hasColumn('orders', 'part_id')) {
                $table->dropForeign(['part_id']);
                $table->dropColumn('part_id');
            }
            
            // Add back the polymorphic columns if they were removed
            if (!Schema::hasColumn('orders', 'orderable_id')) {
                $table->unsignedBigInteger('orderable_id'); // Polymorphic: can be a second_hand_part or a new component
                $table->string('orderable_type'); // Polymorphic: e.g., 'App\Models\SecondHandPart', 'App\Models\Cpu'
            }
            
            // Revert the column changes if needed
            // Note: You might need to specify the exact original column definitions
        });
    }
};