<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimedByAndClaimedEmailToGCListsDevpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('g_c_lists_devps', function (Blueprint $table) {
            $table->string('claimed_by')->after('last_name')->nullable();
            $table->string('claimed_email')->after('claimed_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('g_c_lists_devps', function (Blueprint $table) {
            $table->dropColumn('claimed_by');
            $table->dropColumn('claimed_email');
        });
    }
}
