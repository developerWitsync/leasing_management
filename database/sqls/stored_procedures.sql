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
CREATE DEFINER=`witsyncu_demousr`@`localhost` PROCEDURE `present_value_of_lease_liability`(
IN `payment_year` INT,
IN `payment_month` INT,
IN `base_date` DATE,
IN `asset_id` INT,
IN `payment_id` INT
)
BEGIN

    DECLARE is_escalation_applicable char(10);
    DECLARE total_amount_payable decimal(12, 2) DEFAULT 0;
	  declare payment_date date;
    Declare days_diff INT DEFAULT 0;
    Declare lease_liability FLOAT DEFAULT 0;
    declare discount_rate decimal(25,20) default 0;
    declare payment_name text;
    declare last_payment_month INT;
    declare last_payment_year INT;
    declare purchase_option_price FLOAT;
    #check if the escalation is applicable for this lease if yes than we have to take out the amount from the escalations
    select `escalation_clause_applicable` into is_escalation_applicable
    from `lease` JOIN `lease_assets` ON (`lease`.`id` = `lease_assets`.`lease_id`)
    where `lease_assets`.`id` = asset_id;

    select `payment_escalation`.`is_escalation_applicable` into is_escalation_applicable
    from `payment_escalation`
    where `payment_escalation`.`asset_id` = asset_id AND `payment_escalation`.`payment_id` = payment_id;

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


    #calculate the difference of number of days to do the same we need to have the payment_date
    #for current year and month
    select `date` into payment_date from `lease_asset_payment_dates` where
    `payment_id` = payment_id AND DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%m') = payment_month and DATE_FORMAT(`lease_asset_payment_dates`.`date`,'%Y') = payment_year order by `date` desc limit 0,1;

    set days_diff = datediff(payment_date, base_date);

    #select the discount rate for the current asset
    select `lease_select_discount_rate`.`daily_discount_rate` into discount_rate from `lease_select_discount_rate`
    where `lease_select_discount_rate`.`asset_id` = asset_id;

    #now need to calculate the lease_liability here

    set lease_liability = total_amount_payable / POWER( 1 + (discount_rate) * (1 / 100), days_diff);

    select payment_id,payment_name, total_amount_payable, discount_rate, lease_liability, payment_year, payment_month, is_escalation_applicable,days_diff;

  END IF;

END;
-- --------------------------------------------------------
