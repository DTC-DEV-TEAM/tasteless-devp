<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGCListsDevpsCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_c_lists_devps_customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('confirmed_email')->nullable();
            $table->string('store_concept')->nullable();
            $table->string('otp_code')->nullable();
            $table->integer('email_is_sent')->default('0')->nullable();
            $table->integer('otp_is_matched')->default('0')->nullable();
            $table->integer('is_subscribe')->nullable();
            $table->integer('created_by')->unsigned()->length(10)->nullable();
            $table->integer('updated_by')->unsigned()->length(10)->nullable();
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
        Schema::dropIfExists('g_c_lists_devps_customers');
    }
}
