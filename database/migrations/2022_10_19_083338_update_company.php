<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function($table) {
            $table->integer('status')->default(0)->after('logo_path');
        });

        Schema::table('attributes', function($table) {
            $table->integer('category_id')->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function($table) {
            $table->dropColumn('status');
        });

        Schema::table('attributes', function($table) {
            $table->dropColumn('category_id');
        });
    }
}
