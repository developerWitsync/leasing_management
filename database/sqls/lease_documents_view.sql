CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`witsyncf_flexsin`@`localhost` SQL SECURITY DEFINER VIEW `lease_documents`  AS
(SELECT
    `lease`.`id` AS `lease_id`,
    'Copy Of Signed Contract' as `type`,
    `lease`.`file` AS `file`,
    `lease`.`created_at` AS `upload_date`
FROM
    `lease`
WHERE
    ((`lease`.`file` IS NOT NULL)
        AND (`lease`.`file` <> ''))) UNION (SELECT
    `lease`.`id` AS `lease_id`,
    'Lease Payment Workings' as `type`,
    `lease_assets_payments`.`attachment` AS `file`,
    `lease_assets_payments`.`created_at` AS `upload_date`
FROM
    ((`lease_assets_payments`
    JOIN `lease_assets` ON ((`lease_assets`.`id` = `lease_assets_payments`.`asset_id`)))
    JOIN `lease` ON ((`lease_assets`.`lease_id` = `lease`.`id`)))
WHERE
    ((`lease_assets_payments`.`attachment` IS NOT NULL)
        AND (`lease_assets_payments`.`attachment` <> ''))) UNION (SELECT
    `fair_market_value`.`lease_id` AS `lease_id`,
    'Fair Market Value' as `type`,
    `fair_market_value`.`attachment` AS `file`,
    `fair_market_value`.`created_at` AS `upload_date`
FROM
    `fair_market_value`
WHERE
    ((`fair_market_value`.`attachment` IS NOT NULL)
        AND (`fair_market_value`.`attachment` <> ''))) UNION (SELECT
    `security_deposits`.`lease_id` AS `lease_id`,
    'Security Deposits' as `type`,
    `security_deposits`.`doc` AS `file`,
    `security_deposits`.`created_at` AS `upload_date`
FROM
    `security_deposits`
WHERE
    ((`security_deposits`.`doc` IS NOT NULL)
        AND (`security_deposits`.`doc` <> '')));