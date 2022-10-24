<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('customer_id');
            $table->integer('order_id');
            $table->date('payment_date');
            $table->integer('amount');
            $table->string('invoice')->nullable()->default('Y');
            $table->string('payment_type');
            $table->string('payment_status');
            $table->string('remark')->nullable();
            $table->integer('is_reconciled')->nullable()->default(0);
            $table->integer('action_by');
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
        Schema::dropIfExists('payments');
    }
};
