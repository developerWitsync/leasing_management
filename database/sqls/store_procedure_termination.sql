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

DROP PROCEDURE IF EXISTS `present_value_of_lease_liability_termination`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `present_value_of_lease_liability_termination`(
IN `termination_id` INT,
IN `asset_id` INT,
IN `base_date` DATE)
BEGIN
	DECLARE total_amount_payable decimal(12, 2) DEFAULT 0;
	declare termination_penalty FLOAT;
	declare termination_penalty_date date;
    Declare days_diff INT DEFAULT 0;
    Declare lease_liability FLOAT DEFAULT 0;
    declare discount_rate decimal(25,20) default 0;

	select IFNULL(`lease_termination_option`.`termination_penalty`, 0.0), `lease_termination_option`.`lease_end_date`  into termination_penalty, termination_penalty_date from `lease_termination_option`
        where `lease_termination_option`.`id` = termination_id
        AND `lease_termination_option`.`lease_termination_option_available` = 'yes'
        AND `lease_termination_option`.`termination_penalty_applicable` = 'yes'
        AND `lease_termination_option`.`exercise_termination_option_available` = 'yes';

	IF( termination_penalty IS NOT NULL) THEN
			set total_amount_payable = termination_penalty;
            set days_diff = datediff(termination_penalty_date, base_date);
            #select the discount rate for the current asset
			select `daily_discount_rate` into discount_rate from `lease_select_discount_rate`
			where `lease_select_discount_rate`.`asset_id` = asset_id;

			#now need to calculate the lease_liability here

			set lease_liability = total_amount_payable / POWER(( 1 + discount_rate), days_diff);

            select total_amount_payable, discount_rate, lease_liability, days_diff, termination_penalty;

	END IF;


END;

-- --------------------------------------------------------
