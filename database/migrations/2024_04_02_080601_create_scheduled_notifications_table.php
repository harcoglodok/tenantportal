<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledNotificationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_category_id')->constrained()->onDelete('cascade');
            $table->string('title', 50);
            $table->string('image')->nullable();
            $table->text('message');
            $table->date('date');
            $table->foreignId('created_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users', 'id')->onDelete('cascade');
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
        Schema::drop('scheduled_notifications');
    }
}
