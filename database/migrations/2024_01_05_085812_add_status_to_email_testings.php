<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEmailTestings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_testings', function (Blueprint $table) {
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->nullable()->after('html_email');
            $table->integer('store_logos_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_testings', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('store_logos_id');
        });
    }
}
