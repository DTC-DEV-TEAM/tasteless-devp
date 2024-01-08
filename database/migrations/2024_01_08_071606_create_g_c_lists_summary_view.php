<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGCListsSummaryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS g_c_lists_summary_view;");
    
        DB::statement("
            CREATE VIEW g_c_lists_summary_view AS
            SELECT
                ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS id,
                `uploaded_img`,
                `name`,
                `phone`,
                `email`,
                `campaign_id`,
                `gc_description`,
                `gc_value`,
                `invoice_number`,
                CONCAT(
                    `campaign_id`,
                    ' - ',
                    `qr_reference_number`
                ) AS `gclists`
            FROM
                (
                    SELECT
                        `g_c_lists`.`uploaded_img`,
                        `g_c_lists`.`name`,
                        `g_c_lists`.`phone`,
                        `g_c_lists`.`email`,
                        `g_c_lists`.`campaign_id`,
                        `qr_creations`.`gc_description`,
                        `qr_creations`.`gc_value`,
                        `g_c_lists`.`invoice_number`,
                        `g_c_lists`.`qr_reference_number`
                    FROM
                        `g_c_lists`
                    LEFT JOIN
                        `qr_creations` ON `g_c_lists`.`campaign_id` = `qr_creations`.`id`
    
                    UNION
    
                    SELECT
                        `g_c_lists_devps`.`uploaded_img`,
                        `g_c_lists_devps`.`name`,
                        `g_c_lists_devps`.`phone`,
                        `g_c_lists_devps`.`email`,
                        `g_c_lists_devps`.`campaign_id`,
                        `qr_creations`.`gc_description`,
                        `qr_creations`.`gc_value`,
                        `g_c_lists_devps`.`invoice_number`,
                        `g_c_lists_devps`.`qr_reference_number`
                    FROM
                        `g_c_lists_devps`
                    LEFT JOIN
                        `qr_creations` ON `g_c_lists_devps`.`campaign_id` = `qr_creations`.`id`
                ) AS combined_tables
        ");
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS g_c_lists_summary_view;");
    }
}
