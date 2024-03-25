<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrTypeToQrCreations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qr_creations', function (Blueprint $table) {
            $table->integer('qr_type')->unsigned()->length(10)->nullable()->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qr_creations', function (Blueprint $table) {
            $table->dropColumn('qr_type');
        });
    }
}
