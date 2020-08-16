<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesInForginKeysLectureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('lectures', 'checkout_user_id') || Schema::hasColumn('lectures', 'payed_user_id')) {
            Schema::table('lectures', function (Blueprint $table) {

                $table->dropForeign('lectures_checkout_user_id_foreign');
                $table->dropColumn('checkout_user_id');

                $table->dropForeign('lectures_payed_user_id_foreign');
                $table->dropColumn('payed_user_id');

            });
        }
        Schema::table('lectures', function (Blueprint $table) {
            $table->integer('checkout_user_id')->nullable()->unsigned();
            $table->integer('payed_user_id')->nullable()->unsigned();

            $table->foreign('checkout_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payed_user_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign('lectures_checkout_user_id_foreign');
            $table->dropForeign('lectures_payed_user_id_foreign');
            $table->dropColumn(['checkout_user_id', 'payed_user_id']);
        });
    }
}
