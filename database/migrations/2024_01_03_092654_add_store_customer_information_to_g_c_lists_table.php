<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreCustomerInformationToGCListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('g_c_lists', function (Blueprint $table) {
            $table->integer('generated_from_id')->nullable()->after('email_template_id');
            $table->string('first_name')->nullable()->after('generated_from_id');
            $table->string('last_name')->nullable()->after('first_name');
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
            $table->dropColumn('generated_from_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
}
