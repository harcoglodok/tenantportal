<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->decimal('previous_transaction', 13, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->double('previous_transaction')->change();
        });
    }
};
