
INSERT INTO `subscription_plans` (`id`, `title`, `slug`, `price_plan_type`, `price`, `available_leases`, `available_users`, `hosting_type`, `validity`, `created_at`, `updated_at`, `most_popular`, `annual_discount`) VALUES
(1, 'Trial Plan', 'trial-plan', '1', NULL, 1, 1, 'cloud', 5, '2019-02-26 02:07:59', '2019-02-26 02:07:59', '0', '0.00'),
(2, '5 Lease Assets Plan', '5-lease-assets-plan', '1', '149.00', 5, 1, 'cloud', NULL, '2019-02-26 02:08:40', '2019-02-26 02:08:40', '0', '10.00'),
(3, '15 Lease Assets Plan', '15-lease-assets-plan', '1', '199.00', 15, 2, 'cloud', NULL, '2019-02-26 02:10:38', '2019-02-26 02:10:38', '1', '10.00'),
(4, '25 Lease Assets Plan', '25-lease-assets-plan', '1', '299.00', 25, 3, 'cloud', NULL, '2019-02-26 02:10:56', '2019-02-26 02:10:56', '0', '10.00'),
(5, 'Enterprise Plan', 'enterprise-plan', '2', NULL, NULL, NULL, 'both', NULL, '2019-02-26 02:11:17', '2019-02-26 02:11:17', '0', '0.00');
