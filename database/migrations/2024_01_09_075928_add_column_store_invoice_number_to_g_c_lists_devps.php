<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStoreInvoiceNumberToGCListsDevps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('g_c_lists_devps', function (Blueprint $table) {
            $table->string('store_invoice_number')->nullable()->after('store_concept');
            $table->string('reference_number')->nullable()->after('email_template_id');
            $table->string('g_c_lists_devps_customer_id')->nullable()->after('reference_number');
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
            $table->dropColumn('store_invoice_number');
            $table->dropColumn('reference_number');
            $table->dropColumn('g_c_lists_devps_customer_id');
        });
    }
}
