<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingTransactionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('billing_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users', 'id');
            $table->string('message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billing_transactions');
    }
}
