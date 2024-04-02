<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('device_token')->nullable();
            $table->string('email')->unique();
            $table->string('business_id')->nullable();
            $table->date('birthdate');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->constrained('users', 'id')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->foreignId('blocked_by')->constrained('users', 'id')->nullable();
            $table->string('block_message')->nullable();
            $table->enum('role', ['root', 'admin', 'tenant'])->default('tenant');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
