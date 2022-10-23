<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('bookings', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('cargos', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('category', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('customer', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('enquirys', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('godown', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('orders', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('packing', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('payment', function($table) {
            $table->integer('company_id')->after('id');
        });

        Schema::table('product', function($table) {
            $table->integer('company_id')->after('id');
        });
        
        Schema::table('quotations', function($table) {
            $table->integer('company_id')->after('id');
        });
        Schema::table('stocks', function($table) {
            $table->integer('company_id')->after('id');
        });
        Schema::table('user', function($table) {
            $table->integer('company_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attributes', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('bookings', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('cargos', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('category', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('customer', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('enquirys', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('godown', function($table) {
            $table->dropColumn('company_id');
        });

        Schema::table('orders', function($table) {
            $table->dropColumn('company_id');
        });
        Schema::table('packing', function($table) {
            $table->dropColumn('company_id');
        });
        Schema::table('payment', function($table) {
            $table->dropColumn('company_id');
        });
        Schema::table('product', function($table) {
            $table->dropColumn('company_id');
        });
        Schema::table('quotations', function($table) {
            $table->dropColumn('company_id');
        });
        Schema::table('stocks', function($table) {
            $table->dropColumn('company_id');
        });
        Schema::table('user', function($table) {
            $table->dropColumn('company_id');
        });
    }
}
