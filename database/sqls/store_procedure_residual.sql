DROP PROCEDURE IF EXISTS `present_value_of_lease_liability_residual`;

CREATE DEFINER=`witsyncf_flexsin`@`localhost` PROCEDURE `present_value_of_lease_liability_residual`(
IN `residual_id` INT,
IN `asset_id` INT,
IN `lease_end_date` date,
IN `base_date` DATE
)
BEGIN
	DECLARE total_amount_payable decimal(12, 2) DEFAULT 0;
	declare residual_gurantee_value FLOAT;
    Declare days_diff INT DEFAULT 0;
    Declare lease_liability FLOAT DEFAULT 0;
    declare discount_rate decimal(25,20) default 0;

		#select the residual value gurantee if applicable and add the same to the total_amount_payable.
        select IFNULL(`lease_residual_value_gurantee`.`total_residual_gurantee_value`, 0) into residual_gurantee_value from `lease_residual_value_gurantee`
        where `lease_residual_value_gurantee`.`asset_id` = asset_id  and `lease_residual_value_gurantee`.`any_residual_value_gurantee` = "yes";
        IF (residual_gurantee_value IS NOT NULL) THEN
			set total_amount_payable = residual_gurantee_value;
            set days_diff = datediff(lease_end_date, base_date);
            #select the discount rate for the current asset
			select `daily_discount_rate` into discount_rate from `lease_select_discount_rate`
			where `lease_select_discount_rate`.`asset_id` = asset_id;

			#now need to calculate the lease_liability here

			set lease_liability = total_amount_payable / POWER( 1 + (discount_rate) * (1 / 100), days_diff);

            select total_amount_payable, discount_rate, lease_liability, days_diff, residual_gurantee_value;
		END IF;
END;