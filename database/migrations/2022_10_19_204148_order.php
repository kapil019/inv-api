<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('order_number');
            $table->string('payment_status');
            $table->string('type');
            $table->integer('parent_id')->nullable();
            $table->string('state');
            $table->integer('customer_id');
            $table->decimal('shipping_amount', 12, 4, true)->nullable();
            $table->decimal('packing_amount', 12, 4, true)->nullable();
            $table->decimal('forward_amount', 12, 4, true)->nullable();
            $table->decimal('printing_amount', 12, 4, true)->nullable();
            $table->decimal('discount_amount', 12, 4, true)->nullable();
            $table->decimal('grand_total', 12, 4, true)->nullable();
            $table->decimal('subtotal', 12, 4, true)->nullable();
            $table->decimal('tax_amount', 12, 4, true)->nullable();
            $table->decimal('total_amount', 12, 4, true)->nullable();
            $table->decimal('total_paid', 12, 4, true)->nullable();
            $table->decimal('pending_amount', 12, 4, true)->nullable();
            $table->integer('invoice')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_url')->nullable();
            $table->text('remark')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('orders');
    }
}
