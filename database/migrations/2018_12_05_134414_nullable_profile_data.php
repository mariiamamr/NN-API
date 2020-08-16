<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableProfileData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('tinyinteger', 'integer');
        Schema::table('user_info', function (Blueprint $table) {
            //
            $table->string('nationality', 100)->nullable()->change();
            $table->string('phone', 100)->nullable()->change();
            $table->string('postal_code', 20)->nullable()->change();
            // $table->tinyInteger('exp_years')->default(0)->change();
            $table->float('avg_rate')->default(0)->change();
            $table->float('month_rate')->default(0)->change();
            $table->float('rates_count')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_info', function (Blueprint $table) {
            //
        });
    }
}
