<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreBranchesColumnToGCLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('g_c_lists', function (Blueprint $table) {
            $table->integer('store_concepts_id')->length(10)->unsigned()->nullable()->after('store_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('g_c_lists', function (Blueprint $table) {
            $table->dropColumn('store_concepts_id');
        });
    }
}
