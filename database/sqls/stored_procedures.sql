/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `witsync_lease`
--

--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `present_value_of_lease_liability`(IN `payment_year` INT, IN `payment_month` INT, IN `base_date` DATE, IN `asset_id` INT, IN `payment_id` INT)
BEGIN

    DECLARE is_escalation_applicable char(10);
    DECLARE total_amount_payable decimal(12, 2) DEFAULT 0;
	declare payment_date date;
    Declare days_diff INT DEFAULT 0;
    Declare lease_liability FLOAT DEFAULT 0;
    declare discount_rate INT default 0;
    declare payment_name text;
    declare last_payment_month INT;
    declare last_payment_year INT;
    declare termination_penalty FLOAT;
    declare residual_value_gurantee_value FLOAT;
    declare purchase_option_price FLOAT;
    #check if the escalation is applicable for this lease if yes than we have to take out the amount from the escalations
    select `escalation_clause_applicable` into is_escalation_applicable
    from `lease` JOIN `lease_assets` ON (`lease`.`id` = `lease_assets`.`lease_id`)
    where `lease_assets`.`id` = asset_id;

    select `name` into payment_name from `lease_assets_payments` where `id` = payment_id;

    IF STRCMP(is_escalation_applicable, 'yes') = 0 Then

        select SUM(`payment_escalation_dates`.`total_amount_payable`) into total_amount_payable from `payment_escalation_dates`
        where `payment_escalation_dates`.`payment_id` = payment_id AND `payment_escalation_dates`.`escalation_month` = payment_month AND `payment_escalation_dates`.`escalation_year` = payment_year;

    ELSE
		#escalation is not applied and we need to take out the total amount from the payments
		select SUM(`lease_asset_payment_dates`.`total_payment_amount`) into total_amount_payable from `lease_asset_payment_dates`
        JOIN `lease_assets_payments` ON (`lease_asset_payment_dates`.`payment_id` = `lease_assets_payments`.`id`)
        where `lease_asset_payment_dates`.`payment_id` = payment_id AND DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%m') = payment_month and DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%Y') = payment_year;

    END IF;

	IF total_amount_payable IS NOT NULL THEN


	#check if the current payment is the last payment date..

    select DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%m'),
    DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%Y') into last_payment_month, last_payment_year from `lease_asset_payment_dates`
    where `lease_asset_payment_dates`.`payment_id` =  payment_id order by `lease_asset_payment_dates`.`date` desc limit 0,1;

    IF(last_payment_month = payment_month AND last_payment_year = payment_year) THEN
		#select the termination penalty if applicable and and the same to the total_amount_payable.
        select IFNULL(`lease_termination_option`.`termination_penalty`, 0.0) into termination_penalty from `lease_termination_option`
        where `lease_termination_option`.`asset_id` = asset_id
        AND `lease_termination_option`.`lease_termination_option_available` = 'yes'
        AND `lease_termination_option`.`termination_penalty_applicable` = 'yes'
        AND `lease_termination_option`.`exercise_termination_option_available` = 'yes';
        IF( termination_penalty IS NOT NULL) THEN
			    set total_amount_payable = total_amount_payable + termination_penalty;
        END IF;

        #select the residual value gurantee if applicable and add the same to the total_amount_payable.
        select IFNULL(`lease_residual_value_gurantee`.`total_residual_gurantee_value`, 0) into residual_value_gurantee_value from `lease_residual_value_gurantee`
        where `lease_residual_value_gurantee`.`asset_id` = asset_id;
        IF (residual_value_gurantee_value IS NOT NULL) THEN
			    set total_amount_payable = total_amount_payable + residual_value_gurantee_value;
		    END IF;

        #select purchase option  and add the same to the total_amount_payable.
        select IFNULL(`purchase_option`.`purchase_price`, 0.0) into purchase_option_price from `purchase_option` where
        `purchase_option`.`asset_id` = asset_id AND
        `purchase_option`.`purchase_option_clause` = 'yes' AND
        `purchase_option`.`purchase_option_exerecisable` = 'yes';

        IF(purchase_option_price IS NOT NULL) THEN
			    set total_amount_payable = total_amount_payable + purchase_option_price;
        END IF;


    END IF;

    #calculate the difference of number of days to do the same we need to have the payment_date
    #for current year and month
    select `date` into payment_date from `lease_asset_payment_dates` where
    `payment_id` = payment_id AND DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%m') = payment_month and DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%Y') = payment_year order by `date` desc limit 0,1;

    set days_diff = datediff(payment_date, base_date);

    #select the discount rate for the current asset
    select `discount_rate_to_use` into discount_rate from `lease_select_discount_rate`
    where `lease_select_discount_rate`.`asset_id` = asset_id;

    #now need to calculate the lease_liability here

    set lease_liability = total_amount_payable / POWER(( 1 + ( (discount_rate / 100) / 365 )), days_diff);

    select payment_id,payment_name, total_amount_payable, discount_rate, lease_liability, payment_year, payment_month, is_escalation_applicable,days_diff, termination_penalty,residual_value_gurantee_value, purchase_option_price;

  END IF;

END;

-- --------------------------------------------------------
