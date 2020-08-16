<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('university_degree_user');

        Schema::table('user_info', function (Blueprint $table) {
            $table->integer('university_degree_id')->unsigned()->nullable();

            $table->foreign('university_degree_id')->references('id')->on('university_degrees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_info', 'university_degree_id')) {
            Schema::table('user_info', function (Blueprint $table) {
                $table->dropForeign('user_info_university_degree_id_foreign');
                $table->dropColumn('university_degree_id');
            });
        }
    }
}
