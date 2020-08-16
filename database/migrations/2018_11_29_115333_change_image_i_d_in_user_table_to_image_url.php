<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// use Doctrine;

class ChangeImageIDInUserTableToImageUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'image_id')) {
            Schema::table('users', function (Blueprint $table) {
             //to drop foregin key <table_name>_<column_name>_foregin
                $table->dropForeign('users_image_id_foreign');
                $table->dropColumn('image_id');
                $table->string('image_url');           
            // $table->renameColumn('image_id', 'image_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'image_url')) {
            //
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }
    }
}
