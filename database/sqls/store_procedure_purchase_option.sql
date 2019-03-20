DROP PROCEDURE IF EXISTS `present_value_of_lease_liability_purchase`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `present_value_of_lease_liability_purchase`(
IN `purchase_id` INT,
IN `asset_id` INT,
IN `base_date` DATE
)
BEGIN

    DECLARE total_amount_payable decimal(12, 2) DEFAULT 0;
	declare purchase_option_price FLOAT;
    Declare days_diff INT DEFAULT 0;
    Declare lease_liability FLOAT DEFAULT 0;
    declare discount_rate INT default 0;
    declare expected_purchase_date date;

    select IFNULL(`purchase_option`.`purchase_price`, 0.0), `purchase_option`.`expected_purchase_date` into purchase_option_price, expected_purchase_date from `purchase_option` where
        `purchase_option`.`id` = purchase_id AND
        `purchase_option`.`purchase_option_clause` = 'yes' AND
        `purchase_option`.`purchase_option_exerecisable` = 'yes';

	IF(purchase_option_price IS NOT NULL) THEN

			set total_amount_payable = purchase_option_price;
            set days_diff = datediff(expected_purchase_date, base_date);

			#select the discount rate for the current asset
			select `daily_discount_rate` into discount_rate from `lease_select_discount_rate`
			where `lease_select_discount_rate`.`asset_id` = asset_id;

			#now need to calculate the lease_liability here

			set lease_liability = total_amount_payable / POWER(( 1 + discount_rate), days_diff);

            select total_amount_payable, discount_rate, lease_liability, days_diff, purchase_option_price;

	END IF;
END;