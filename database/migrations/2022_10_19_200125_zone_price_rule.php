<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ZonePriceRule extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_price_rule', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name');
            $table->integer('zone_id');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->integer('product_variant_id');
            $table->string('discount_type'); // P/F
            $table->integer('discount');
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
        Schema::dropIfExists('zone_price_rule');
    }
}
