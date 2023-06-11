<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_ids', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->integer('created_by')->unsigned()->length(10)->nullable();
            $table->integer('updated_by')->unsigned()->length(10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_ids');
    }
}
