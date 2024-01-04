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
            $table->integer('store_status')->nullable()->after('email_template_id');
            $table->integer('egc_value_id')->nullable()->after('store_status');
            $table->integer('generated_from_id')->nullable()->after('store_status');
            $table->string('first_name')->nullable()->after('generated_from_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->integer('st_cashier_id')->nullable()->after('accounting_is_audit');
            $table->timestamp('st_cashier_date_transact')->nullable()->after('st_cashier_id');
            $table->integer('st_oic_id')->nullable()->after('st_cashier_date_transact');
            $table->timestamp('st_oic_date_transact')->nullable()->after('st_oic_id');
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
            $table->dropColumn('store_status');
            $table->dropColumn('egc_value_id');
            $table->dropColumn('generated_from_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('st_cashier_id');
            $table->dropColumn('st_cashier_date_transact');
            $table->dropColumn('st_oic_id');
            $table->dropColumn('st_oic_date_transact');
        });
    }
}
