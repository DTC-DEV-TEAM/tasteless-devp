select
    row_number() over (
        order by (
                select NULL
            )
    ) AS id,
    combined_tables.uploaded_img AS uploaded_img,
    combined_tables.name AS name,
    combined_tables.phone AS phone,
    combined_tables.email AS email,
    combined_tables.campaign_name AS campaign_id,
    combined_tables.gc_description AS gc_description,
    combined_tables.gc_value AS gc_value,
    combined_tables.invoice_number AS invoice_number,
    combined_tables.pos_terminal AS pos_terminal,
    combined_tables.accounting_is_audit AS accounting_is_audit,
    concat(
        coalesce(
            combined_tables.campaign_name, 'In-store EGC '
        ), ' - ', combined_tables.qr_reference_number
    ) AS gclists,
    case
        when combined_tables.source_table = 'g_c_lists_devps' then '3'
        when combined_tables.source_table = 'g_c_lists' then combined_tables.qr_campaign
        else combined_tables.campaign_id
    end AS campaign_types_id
from (
        select
            gc_redemption_system.g_c_lists.uploaded_img AS uploaded_img, gc_redemption_system.g_c_lists.name AS name, gc_redemption_system.g_c_lists.phone AS phone, gc_redemption_system.g_c_lists.email AS email, gc_redemption_system.g_c_lists.campaign_id AS campaign_id, gc_redemption_system.qr_creations.gc_description AS gc_description, gc_redemption_system.qr_creations.gc_value AS gc_value, gc_redemption_system.g_c_lists.invoice_number AS invoice_number, gc_redemption_system.g_c_lists.qr_reference_number AS qr_reference_number, 'g_c_lists' AS source_table, gc_redemption_system.qr_creations.campaign_type_id AS qr_campaign, gc_redemption_system.qr_creations.campaign_id AS campaign_name, gc_redemption_system.g_c_lists.accounting_is_audit AS accounting_is_audit, gc_redemption_system.g_c_lists.pos_terminal AS pos_terminal
        from (
                gc_redemption_system.g_c_lists
                left join gc_redemption_system.qr_creations on (
                    gc_redemption_system.g_c_lists.campaign_id = gc_redemption_system.qr_creations.id
                )
            )
        union
        select
            gc_redemption_system.g_c_lists_devps.uploaded_img AS uploaded_img, gc_redemption_system.g_c_lists_devps.name AS name, gc_redemption_system.g_c_lists_devps.phone AS phone, gc_redemption_system.g_c_lists_devps.email AS email, gc_redemption_system.g_c_lists_devps.campaign_id AS campaign_id, gc_redemption_system.qr_creations.gc_description AS gc_description, gc_redemption_system.egc_value_types.value AS value, gc_redemption_system.g_c_lists_devps.invoice_number AS invoice_number, gc_redemption_system.g_c_lists_devps.qr_reference_number AS qr_reference_number, 'g_c_lists_devps' AS source_table, gc_redemption_system.qr_creations.campaign_type_id AS qr_campaign, gc_redemption_system.qr_creations.campaign_id AS campaign_name, gc_redemption_system.g_c_lists_devps.accounting_is_audit AS accounting_is_audit, gc_redemption_system.g_c_lists_devps.pos_terminal AS pos_terminal
        from (
                (
                    gc_redemption_system.g_c_lists_devps
                    left join gc_redemption_system.qr_creations on (
                        gc_redemption_system.g_c_lists_devps.campaign_id = gc_redemption_system.qr_creations.id
                    )
                )
                left join gc_redemption_system.egc_value_types on (
                    gc_redemption_system.g_c_lists_devps.egc_value_id = gc_redemption_system.egc_value_types.id
                )
            )
    ) combined_tables