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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('parent_item_id');
            $table->integer('product_id');
            $table->text('product_options');
            $table->string('product_type');
            $table->string('list_no');
            $table->integer('name');
            $table->text('description');
            $table->integer('quantity_ordered')->nullable();
            $table->integer('quantity_pending')->nullable();
            $table->integer('quantity_canceled')->nullable();
            $table->integer('quantity_invoiced')->nullable();
            $table->integer('quantity_shipped')->nullable();
            $table->decimal('price', 12, 4, true)->nullable();
            $table->decimal('shipping_amount', 12, 4, true)->nullable();
            $table->decimal('discount_amount', 12, 4, true)->nullable();
            $table->decimal('tax_percent', 12, 4, true)->nullable();
            $table->decimal('tax_amount', 12, 4, true)->nullable();
            $table->decimal('row_total', 12, 4, true)->nullable();
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
        Schema::dropIfExists('order_items');
    }
};
