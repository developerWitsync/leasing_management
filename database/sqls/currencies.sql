-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2018 at 12:18 PM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.1.24-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `code`, `symbol`, `thousand_separator`, `decimal_separator`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Leke', 'ALL', 'Lek', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(3, 'Afghanis', 'AF', '؋', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(4, 'Pesos', 'ARS', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(5, 'Guilders', 'AWG', 'ƒ', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(6, 'Dollars', 'AUD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(7, 'New Manats', 'AZ', 'ман', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(8, 'Dollars', 'BSD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(9, 'Dollars', 'BBD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(10, 'Rubles', 'BYR', 'p.', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(12, 'Dollars', 'BZD', 'BZ$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(13, 'Dollars', 'BMD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(14, 'Bolivianos', 'BOB', '$b', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(15, 'Convertible Marka', 'BAM', 'KM', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(16, 'Pula\'s', 'BWP', 'P', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(17, 'Leva', 'BG', 'лв', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(18, 'Reais', 'BRL', 'R$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(20, 'Dollars', 'BND', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(21, 'Riels', 'KHR', '៛', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(22, 'Dollars', 'CAD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(23, 'Dollars', 'KYD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(24, 'Pesos', 'CLP', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(25, 'Yuan Renminbi', 'CNY', '¥', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(26, 'Pesos', 'COP', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(27, 'Colón', 'CRC', '₡', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(28, 'Kuna', 'HRK', 'kn', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(29, 'Pesos', 'CUP', '₱', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(31, 'Koruny', 'CZK', 'Kč', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(32, 'Kroner', 'DKK', 'kr', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(33, 'Pesos', 'DOP ', 'RD$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(34, 'Dollars', 'XCD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(35, 'Pounds', 'EGP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(36, 'Colones', 'SVC', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(39, 'Pounds', 'FKP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(40, 'Dollars', 'FJD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(42, 'Cedis', 'GHC', '¢', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(43, 'Pounds', 'GIP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(45, 'Quetzales', 'GTQ', 'Q', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(46, 'Pounds', 'GGP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(47, 'Dollars', 'GYD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(49, 'Lempiras', 'HNL', 'L', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(50, 'Dollars', 'HKD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(51, 'Forint', 'HUF', 'Ft', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(52, 'Kronur', 'ISK', 'kr', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(53, 'Rupees', 'INR', 'Rp', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(54, 'Rupiahs', 'IDR', 'Rp', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(55, 'Rials', 'IRR', '﷼', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(57, 'Pounds', 'IMP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(58, 'New Shekels', 'ILS', '₪', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(60, 'Dollars', 'JMD', 'J$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(61, 'Yen', 'JPY', '¥', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(62, 'Pounds', 'JEP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(63, 'Tenge', 'KZT', 'лв', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(66, 'Soms', 'KGS', 'лв', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(67, 'Kips', 'LAK', '₭', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(68, 'Lati', 'LVL', 'Ls', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(69, 'Pounds', 'LBP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(70, 'Dollars', 'LRD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(72, 'Litai', 'LTL', 'Lt', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(74, 'Denars', 'MKD', 'ден', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(75, 'Ringgits', 'MYR', 'RM', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(77, 'Rupees', 'MUR', '₨', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(78, 'Pesos', 'MX', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(79, 'Tugriks', 'MNT', '₮', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(80, 'Meticais', 'MZ', 'MT', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(81, 'Dollars', 'NAD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(82, 'Rupees', 'NPR', '₨', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(83, 'Guilders', 'ANG', 'ƒ', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(85, 'Dollars', 'NZD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(86, 'Cordobas', 'NIO', 'C$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(87, 'Nairas', 'NG', '₦', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(88, 'Won', 'KPW', '₩', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(89, 'Krone', 'NOK', 'kr', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(90, 'Rials', 'OMR', '﷼', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(91, 'Rupees', 'PKR', '₨', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(92, 'Balboa', 'PAB', 'B/.', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(93, 'Guarani', 'PYG', 'Gs', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(94, 'Nuevos Soles', 'PE', 'S/.', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(95, 'Pesos', 'PHP', 'Php', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(96, 'Zlotych', 'PL', 'zł', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(97, 'Rials', 'QAR', '﷼', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(98, 'New Lei', 'RO', 'lei', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(99, 'Rubles', 'RUB', 'руб', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(100, 'Pounds', 'SHP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(101, 'Riyals', 'SAR', '﷼', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(102, 'Dinars', 'RSD', 'Дин.', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(103, 'Rupees', 'SCR', '₨', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(104, 'Dollars', 'SGD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(106, 'Dollars', 'SBD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(107, 'Shillings', 'SOS', 'S', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(108, 'Rand', 'ZAR', 'R', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(109, 'Won', 'KRW', '₩', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(111, 'Rupees', 'LKR', '₨', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(112, 'Kronor', 'SEK', 'kr', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(113, 'Francs', 'CHF', 'CHF', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(114, 'Dollars', 'SRD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(115, 'Pounds', 'SYP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(116, 'New Dollars', 'TWD', 'NT$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(117, 'Baht', 'THB', '฿', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(118, 'Dollars', 'TTD', 'TT$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(119, 'Lira', 'TRY', 'TL', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(120, 'Liras', 'TRL', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(121, 'Dollars', 'TVD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(122, 'Hryvnia', 'UAH', '₴', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(123, 'Pounds', 'GBP', '£', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(124, 'Dollars', 'USD', '$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(125, 'Pesos', 'UYU', '$U', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(126, 'Sums', 'UZS', 'лв', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(127, 'Euro', 'EUR', '€', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(128, 'Bolivares Fuertes', 'VEF', 'Bs', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(129, 'Dong', 'VND', '₫', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(130, 'Rials', 'YER', '﷼', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49'),
(131, 'Zimbabwe Dollars', 'ZWD', 'Z$', ',', '.', '1', '2018-12-13 05:02:49', '2018-12-13 05:02:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;