select
    `combined_tables`.`uploaded_img` AS `uploaded_img`,
    COALESCE(`combined_tables`.`name`, `combined_tables`.`claimed_by`) AS `name`,
    `combined_tables`.`phone` AS `phone`,
    COALESCE(`combined_tables`.`email`, `combined_tables`.`claimed_email`) AS `email`,
    `combined_tables`.`claimed_email`,
    `combined_tables`.`campaign_name` AS `campaign_id`,
    `combined_tables`.`gc_description` AS `gc_description`,
    `combined_tables`.`gc_value` AS `gc_value`,
    `combined_tables`.`invoice_number` AS `invoice_number`,
    `combined_tables`.`pos_terminal` AS `pos_terminal`,
    `combined_tables`.`accounting_is_audit` AS `accounting_is_audit`,
    concat(
        coalesce(
            `combined_tables`.`campaign_name`, 'In-store EGC '
        ), ' - ', `combined_tables`.`qr_reference_number`
    ) AS `gclists`,
    case
        when `combined_tables`.`source_table` = 'g_c_lists_devps' then '3'
        when `combined_tables`.`source_table` = 'g_c_lists' then `combined_tables`.`qr_campaign`
        else `combined_tables`.`campaign_id`
    end AS `campaign_types_id`
from (
        select
            `g_c_lists`.`uploaded_img` AS `uploaded_img`, `g_c_lists`.`name` AS `name`, `g_c_lists`.`phone` AS `phone`, `g_c_lists`.`email` AS `email`, `g_c_lists`.`campaign_id` AS `campaign_id`, `qr_creations`.`gc_description` AS `gc_description`, `qr_creations`.`gc_value` AS `gc_value`, `g_c_lists`.`invoice_number` AS `invoice_number`, `g_c_lists`.`qr_reference_number` AS `qr_reference_number`, 'g_c_lists' AS `source_table`, `qr_creations`.`campaign_type_id` AS `qr_campaign`, `qr_creations`.`campaign_id` AS `campaign_name`, `g_c_lists`.`accounting_is_audit` AS `accounting_is_audit`, `g_c_lists`.`pos_terminal` AS `pos_terminal`, null as `claimed_by`, null as `claimed_email`
        from (
                `g_c_lists`
                left join `qr_creations` on (
                    `g_c_lists`.`campaign_id` = `qr_creations`.`id`
                )
            )
        union
        select
            `g_c_lists_devps`.`uploaded_img` AS `uploaded_img`, `g_c_lists_devps`.`name` AS `name`, `g_c_lists_devps`.`phone` AS `phone`, `g_c_lists_devps`.`email` AS `email`, `g_c_lists_devps`.`campaign_id` AS `campaign_id`, `qr_creations`.`gc_description` AS `gc_description`, `egc_value_types`.`value` AS `value`, `g_c_lists_devps`.`invoice_number` AS `invoice_number`, `g_c_lists_devps`.`qr_reference_number` AS `qr_reference_number`, 'g_c_lists_devps' AS `source_table`, `qr_creations`.`campaign_type_id` AS `qr_campaign`, `qr_creations`.`campaign_id` AS `campaign_name`, `g_c_lists_devps`.`accounting_is_audit` AS `accounting_is_audit`, `g_c_lists_devps`.`pos_terminal` AS `pos_terminal`, `g_c_lists_devps`.`claimed_by`, `g_c_lists_devps`.`claimed_email`
        from (
                (
                    `g_c_lists_devps`
                    left join `qr_creations` on (
                        `g_c_lists_devps`.`campaign_id` = `qr_creations`.`id`
                    )
                )
                left join `egc_value_types` on (
                    `g_c_lists_devps`.`egc_value_id` = `egc_value_types`.`id`
                )
            )
    ) `combined_tables`