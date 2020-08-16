<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageIdInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('image_id')->nullable()->unsigned();

            $table->foreign('image_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'image_id')) {
            //
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_image_id_foreign');
                $table->dropColumn('image_id');
            });
        }
    }
}
