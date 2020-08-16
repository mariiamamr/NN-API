<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLectureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->integer('checkout_user_id')->unsigned()->nullable()->change();
            $table->integer('payed_user_id')->unsigned()->nullable()->change();
            $table->json('completed_at')->nullable()->change();

            $table->foreign('checkout_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payed_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lectures', function (Blueprint $table) {
           //
        });
    }
}
