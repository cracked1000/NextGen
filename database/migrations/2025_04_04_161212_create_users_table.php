<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('customer'); // Roles: admin, customer, seller
            $table->string('status')->default('active'); // Status: active, inactive
            $table->string('address')->nullable(); // Add address
            $table->string('Zipcode')->nullable(); // Add Zipcode
            $table->string('phone_number')->nullable(); // Add phone_number
            $table->string('optional_phone_number')->nullable(); // Add optional_phone_number
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}