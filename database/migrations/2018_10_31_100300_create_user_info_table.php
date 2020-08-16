<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('nationality', 100);
            $table->string('phone', 100);
            $table->string('postal_code', 20);
            $table->tinyInteger('exp_years')->nullable();
            $table->string('exp_desc')->nullable();
            $table->json('payment_info')->nullable();
            $table->float('avg_rate');
            $table->float('month_rate');
            $table->integer('rank')->default(0);
            $table->integer('rates_count');
            $table->json('courses')->nullable();
            $table->json('certifications')->nullable();
            $table->string('master_degree')->nullable();
            $table->json('weekly')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_info');
    }
}
