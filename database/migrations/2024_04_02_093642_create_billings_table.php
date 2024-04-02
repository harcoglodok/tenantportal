<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no');
            $table->string('month');
            $table->string('year');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->double('s4_mbase_amt')->default(0);
            $table->double('s4_mtax_amt')->default(0);
            $table->double('sd_mbase_amt')->default(0);
            $table->double('service_charge')->default(0);
            $table->double('sinking_fund')->default(0);
            $table->double('electric_previous')->default(0);
            $table->double('electric_current')->default(0);
            $table->double('electric_read')->default(0);
            $table->double('electric_fixed')->default(0);
            $table->double('electric_administration')->default(0);
            $table->double('electric_tax')->default(0);
            $table->double('electric_total')->default(0);
            $table->double('mcb')->default(0);
            $table->double('water_previous')->default(0);
            $table->double('water_current')->default(0);
            $table->double('water_read')->default(0);
            $table->double('water_fixed')->default(0);
            $table->double('water_mbase')->default(0);
            $table->double('water_administration')->default(0);
            $table->double('water_tax')->default(0);
            $table->double('water_total')->default(0);
            $table->double('total')->default(0);
            $table->string('tube')->default('');
            $table->string('panin')->default('');
            $table->string('bca')->default('');
            $table->string('cimb')->default('');
            $table->string('mandiri')->default('');
            $table->double('add_charge')->default(0);
            $table->double('previous_transaction')->default(0);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
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
        Schema::drop('billings');
    }
}
