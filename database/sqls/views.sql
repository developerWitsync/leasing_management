
--
-- Structure for view `pv_calculus_view_when_escalation_applied`
--

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`witsyncf_flexsin`@`localhost` SQL SECURITY DEFINER VIEW `pv_calculus_view_when_escalation_applied`  AS  select `payment_escalation_dates`.`total_amount_payable` AS `total_amount_payable`,`payment_escalation_dates`.`escalation_year` AS `year`,`payment_escalation_dates`.`escalation_month` AS `month`,`payment_escalation_dates`.`payment_id` AS `payment_id`,`payment_escalation_dates`.`value_escalated` AS `value_escalated`,`lease_asset_payment_dates`.`date` AS `payment_date`,`lease_asset_payment_dates`.`asset_id` AS `asset_id`,`lease_assets_payments`.`name` AS `payment_name`,`lease_select_discount_rate`.`daily_discount_rate` AS `daily_discount_rate` from (((`payment_escalation_dates` join `lease_asset_payment_dates` on(((`lease_asset_payment_dates`.`payment_id` = `payment_escalation_dates`.`payment_id`) and (date_format(`lease_asset_payment_dates`.`date`,'%m') = `payment_escalation_dates`.`escalation_month`) and (date_format(`lease_asset_payment_dates`.`date`,'%Y') = `payment_escalation_dates`.`escalation_year`)))) join `lease_assets_payments` on((`lease_assets_payments`.`id` = `payment_escalation_dates`.`payment_id`))) join `lease_select_discount_rate` on((`lease_select_discount_rate`.`asset_id` = `lease_asset_payment_dates`.`asset_id`))) ;

--
-- Structure for view `pv_calculus_view_when_escalation_not_applied`
--

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`witsyncf_flexsin`@`localhost` SQL SECURITY DEFINER VIEW `pv_calculus_view_when_escalation_not_applied`  AS  select `lease_asset_payment_dates`.`total_payment_amount` AS `total_amount_payable`,date_format(`lease_asset_payment_dates`.`date`,'%Y') AS `year`,date_format(`lease_asset_payment_dates`.`date`,'%m') AS `month`,`lease_asset_payment_dates`.`payment_id` AS `payment_id`,`lease_asset_payment_dates`.`date` AS `payment_date`,`lease_asset_payment_dates`.`asset_id` AS `asset_id`,`lease_assets_payments`.`name` AS `payment_name`,`lease_select_discount_rate`.`daily_discount_rate` AS `daily_discount_rate` from ((`lease_asset_payment_dates` join `lease_assets_payments` on((`lease_asset_payment_dates`.`payment_id` = `lease_assets_payments`.`id`))) join `lease_select_discount_rate` on((`lease_select_discount_rate`.`asset_id` = `lease_asset_payment_dates`.`asset_id`))) ;
