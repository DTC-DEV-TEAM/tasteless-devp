-- Active: 1701236155154@@127.0.0.1@3306@gc_redemption_system
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
    `pos_terminal`,
    `status`,
    CONCAT(
        `campaign_name`,
        ' - ',
        `qr_reference_number`
    ) AS `gclists`,
    CASE
        WHEN `combined_tables`.`source_table` = 'g_c_lists_devps' THEN '3'
        WHEN `combined_tables`.`source_table` = 'g_c_lists' THEN `combined_tables`.`qr_campaign`
        ELSE `combined_tables`.`campaign_id`
    END AS `campaign_types_id`
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
            `g_c_lists`.`qr_reference_number`,
            'g_c_lists' AS `source_table`,
            `qr_creations`.`campaign_type_id` AS 'qr_campaign',
            `qr_creations`.`campaign_id` AS 'campaign_name',
            `g_c_lists`.`status`,
            `g_c_lists`.`pos_terminal`
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
            `g_c_lists_devps`.`qr_reference_number`,
            'g_c_lists_devps' AS `source_table`,
            `qr_creations`.`campaign_type_id` AS 'qr_campaign',
            `qr_creations`.`campaign_id` AS 'campaign_name',
            `g_c_lists_devps`.`status`,
            `g_c_lists_devps`.`pos_terminal`


        FROM
            `g_c_lists_devps`
        LEFT JOIN
            `qr_creations` ON `g_c_lists_devps`.`campaign_id` = `qr_creations`.`id`
    ) AS combined_tables