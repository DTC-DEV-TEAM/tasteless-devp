<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGCListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_c_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id')->nullable();
            $table->integer('email_template_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('email_is_sent')->default('0')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_type')->nullable();
            $table->string('other_id_type')->nullable();
            $table->string('customer_reference_number')->nullable();
            $table->string('qr_reference_number')->nullable();
            $table->string('store_concept')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('redeem')->nullable();
            $table->string('uploaded_img')->nullable();
            $table->string('status')->nullable();
            $table->string('cashier_name')->nullable();
            $table->timestamp('cashier_date_transact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('g_c_lists');
    }
}
