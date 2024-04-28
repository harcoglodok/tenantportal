<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('photo', 255);
            $table->enum('status', ['waiting','replied','done']);
            $table->foreignId('created_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('complaint_categories', 'id')->onDelete('cascade');
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
        Schema::drop('complaints');
    }
}
