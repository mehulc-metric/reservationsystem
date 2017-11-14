-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2017 at 06:23 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservation_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_master`
--

CREATE TABLE `log_master` (
  `log_id` int(10) NOT NULL,
  `login_id` int(10) NOT NULL,
  `ip_address` varchar(40) NOT NULL,
  `browser` text NOT NULL,
  `session_id` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_aauth_perms`
--

CREATE TABLE `res_aauth_perms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `defination` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_aauth_perms`
--

INSERT INTO `res_aauth_perms` (`id`, `name`, `defination`) VALUES
(1, 'view', 'User Can Access View feature.!!'),
(2, 'add', 'User Can Access add feature.!!'),
(3, 'edit', 'User Can Access Edit feature.!!'),
(4, 'delete', 'User Can Access Delete feature.!!');

-- --------------------------------------------------------

--
-- Table structure for table `res_aauth_perm_to_group`
--

CREATE TABLE `res_aauth_perm_to_group` (
  `perm_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `component_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_aauth_perm_to_group`
--

INSERT INTO `res_aauth_perm_to_group` (`perm_id`, `role_id`, `module_id`, `component_name`) VALUES
(1, 3, 4, 'ReservationSystem'),
(1, 3, 6, 'ReservationSystem'),
(1, 3, 7, 'ReservationSystem'),
(1, 4, 4, 'ReservationSystem'),
(1, 4, 5, 'ReservationSystem'),
(1, 4, 6, 'ReservationSystem'),
(1, 4, 8, 'ReservationSystem'),
(1, 5, 6, 'ReservationSystem'),
(1, 2, 3, 'ReservationSystem'),
(1, 2, 4, 'ReservationSystem'),
(1, 2, 6, 'ReservationSystem'),
(1, 2, 7, 'ReservationSystem'),
(1, 2, 8, 'ReservationSystem'),
(1, 1, 1, 'ReservationSystem'),
(2, 1, 1, 'ReservationSystem'),
(3, 1, 1, 'ReservationSystem'),
(4, 1, 1, 'ReservationSystem'),
(1, 1, 2, 'ReservationSystem'),
(2, 1, 2, 'ReservationSystem'),
(3, 1, 2, 'ReservationSystem'),
(4, 1, 2, 'ReservationSystem'),
(1, 1, 3, 'ReservationSystem'),
(2, 1, 3, 'ReservationSystem'),
(3, 1, 3, 'ReservationSystem'),
(4, 1, 3, 'ReservationSystem'),
(1, 1, 4, 'ReservationSystem'),
(2, 1, 4, 'ReservationSystem'),
(3, 1, 4, 'ReservationSystem'),
(4, 1, 4, 'ReservationSystem'),
(1, 1, 5, 'ReservationSystem'),
(2, 1, 5, 'ReservationSystem'),
(3, 1, 5, 'ReservationSystem'),
(4, 1, 5, 'ReservationSystem'),
(1, 1, 6, 'ReservationSystem'),
(2, 1, 6, 'ReservationSystem'),
(3, 1, 6, 'ReservationSystem'),
(4, 1, 6, 'ReservationSystem'),
(1, 1, 7, 'ReservationSystem'),
(2, 1, 7, 'ReservationSystem'),
(3, 1, 7, 'ReservationSystem'),
(4, 1, 7, 'ReservationSystem'),
(1, 1, 8, 'ReservationSystem'),
(2, 1, 8, 'ReservationSystem'),
(3, 1, 8, 'ReservationSystem'),
(4, 1, 8, 'ReservationSystem'),
(1, 1, 9, 'ReservationSystem'),
(2, 1, 9, 'ReservationSystem'),
(3, 1, 9, 'ReservationSystem'),
(4, 1, 9, 'ReservationSystem'),
(1, 1, 10, 'ReservationSystem'),
(2, 1, 10, 'ReservationSystem'),
(3, 1, 10, 'ReservationSystem'),
(4, 1, 10, 'ReservationSystem');

-- --------------------------------------------------------

--
-- Table structure for table `res_accesscontroloperation`
--

CREATE TABLE `res_accesscontroloperation` (
  `IsPush` tinyint(4) DEFAULT NULL,
  `IsNext` tinyint(4) DEFAULT NULL,
  `Id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_accesscontroloperation`
--

INSERT INTO `res_accesscontroloperation` (`IsPush`, `IsNext`, `Id`) VALUES
(0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `res_ci_sessions`
--

CREATE TABLE `res_ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_ci_sessions`
--

INSERT INTO `res_ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('jg7cks6qumcjh7kshajnf0rmhg4ajp2q', '10.1.5.1', 1508742688, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323430323b7265736572766174696f6e5f61646d696e5f73657373696f6e7c613a353a7b733a343a226e616d65223b733a353a224d6568756c223b733a383a2261646d696e5f6964223b733a313a2231223b733a31313a2261646d696e5f656d61696c223b733a32343a226d6568756c2e706174656c40632d6d65747269632e636f6d223b733a31303a2261646d696e5f74797065223b733a313a2231223b733a363a22616374697665223b623a313b7d726f6c656d61737465725f646174617c613a363a7b733a393a22736f72746669656c64223b733a373a22726f6c655f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a353b7d6d6f64756c656d61737465725f646174617c613a363a7b733a393a22736f72746669656c64223b733a393a226d6f64756c655f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a31303b7d73657474696e675f63757272656e745f7461627c733a32333a2273657474696e675f726f6c655f7065726d697373696f6e223b),
('lknaontrnimnd7ksr6gar8266ifltr2v', '52.27.2.86', 1508742535, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323533343b),
('d2vgkj1qvgk2v9el0b839p6insu90mkv', '52.27.2.86', 1508742574, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323537343b),
('f7q6hmmkl07s37jctuel0ro7512jalf7', '52.27.2.86', 1508742583, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323538333b),
('kh9t5d9vhemgdidh55rml1ki5qfsuiga', '150.70.188.168', 1508742641, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323634313b),
('94uuekhfnt2e5cncnloclfnhm58jac0g', '52.27.2.86', 1508742714, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323731343b),
('u8vun4j6v4ce4p8kuckn48qvsistkjj5', '52.27.2.86', 1508742719, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323731393b),
('ns04os3acgjfsslaqhbqcotk9hi4rqqg', '52.27.2.86', 1508742729, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323732393b),
('8efl57u604o8q1gnau0vbbml24d8b1c9', '10.1.5.1', 1508742754, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530383734323732393b7265736572766174696f6e5f61646d696e5f73657373696f6e7c613a353a7b733a343a226e616d65223b733a353a224d6568756c223b733a383a2261646d696e5f6964223b733a313a2231223b733a31313a2261646d696e5f656d61696c223b733a32343a226d6568756c2e706174656c40632d6d65747269632e636f6d223b733a31303a2261646d696e5f74797065223b733a313a2231223b733a363a22616374697665223b623a313b7d726f6c656d61737465725f646174617c613a363a7b733a393a22736f72746669656c64223b733a373a22726f6c655f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a353b7d6d6f64756c656d61737465725f646174617c613a363a7b733a393a22736f72746669656c64223b733a393a226d6f64756c655f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a31303b7d73657474696e675f63757272656e745f7461627c733a32333a2273657474696e675f726f6c655f7065726d697373696f6e223b),
('3gih5nqrflg47hhveu32jj2212eqkeu5', '::1', 1509689861, 0x5f5f63695f6c6173745f726567656e65726174657c693a313530393638393830303b7265736572766174696f6e5f61646d696e5f73657373696f6e7c613a353a7b733a343a226e616d65223b733a353a224d6568756c223b733a383a2261646d696e5f6964223b733a313a2231223b733a31313a2261646d696e5f656d61696c223b733a32343a226d6568756c2e706174656c40632d6d65747269632e636f6d223b733a31303a2261646d696e5f74797065223b733a313a2231223b733a363a22616374697665223b623a313b7d726f6c656d61737465725f646174617c613a363a7b733a393a22736f72746669656c64223b733a373a22726f6c655f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a353b7d6d6f64756c656d61737465725f646174617c613a363a7b733a393a22736f72746669656c64223b733a393a226d6f64756c655f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a31303b7d5265736572766564557365724c6973745f736f7274736561726368706167655f646174617c613a363a7b733a393a22736f72746669656c64223b733a31393a22757365725f7265736572766174696f6e5f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a303b7d43616e63656c6c656452657365727665644c6973745f736f7274736561726368706167655f646174617c613a363a7b733a393a22736f72746669656c64223b733a31393a22757365725f7265736572766174696f6e5f6964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a303b7d75706c6f61645f7a69705f736f7274736561726368706167655f646174617c613a363a7b733a393a22736f72746669656c64223b733a323a226964223b733a363a22736f72746279223b733a343a2264657363223b733a31303a2273656172636874657874223b733a303a22223b733a373a2270657270616765223b733a323a223130223b733a31313a227572695f7365676d656e74223b4e3b733a31303a22746f74616c5f726f7773223b693a303b7d);

-- --------------------------------------------------------

--
-- Table structure for table `res_config`
--

CREATE TABLE `res_config` (
  `config_key` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `res_config`
--

INSERT INTO `res_config` (`config_key`, `value`) VALUES
('no_of_slot_per_hour', '15'),
('no_of_people_per_hour', '4'),
('amount', '10'),
('vat', '10');

-- --------------------------------------------------------

--
-- Table structure for table `res_group_reservation`
--

CREATE TABLE `res_group_reservation` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `no_of_people` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_hourly_time_slot`
--

CREATE TABLE `res_hourly_time_slot` (
  `hourly_ts_id` int(11) NOT NULL COMMENT 'PK',
  `weekly_ts_id` int(11) NOT NULL COMMENT 'FK-res_weekly_time_slot',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_reservable` tinyint(1) NOT NULL COMMENT '1- reservable,0-non reservable',
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_language_master`
--

CREATE TABLE `res_language_master` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_language_master`
--

INSERT INTO `res_language_master` (`language_id`, `language_name`, `name`) VALUES
(1, 'english', 'English'),
(2, 'spanish', 'CASTELLANO'),
(3, 'catala', 'CATALA');

-- --------------------------------------------------------

--
-- Table structure for table `res_module_master`
--

CREATE TABLE `res_module_master` (
  `module_id` int(11) NOT NULL,
  `component_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `module_unique_name` varchar(100) NOT NULL,
  `controller_name` varchar(150) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-active,0-inactive',
  `created_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_module_master`
--

INSERT INTO `res_module_master` (`module_id`, `component_name`, `module_name`, `module_unique_name`, `controller_name`, `created_date`, `status`, `created_by`, `updated_date`, `updated_by`) VALUES
(1, 'ReservationSystem', 'Rolemaster', 'Rolemaster', 'Rolemaster', '2017-06-14 00:00:00', 1, 1, '2017-06-15 01:35:18', 1),
(2, 'ReservationSystem', 'ModuleMaster', 'ModuleMaster', 'ModuleMaster', '2017-06-14 00:00:00', 1, 1, '0000-00-00 00:00:00', 0),
(3, 'ReservationSystem', 'User', 'User', 'User', '2017-06-14 03:17:55', 1, 1, '0000-00-00 00:00:00', 0),
(4, 'ReservationSystem', 'Timeshedule', 'Timeshedule', 'Timeshedule', '2017-06-16 09:34:29', 1, 1, '0000-00-00 00:00:00', 0),
(5, 'ReservationSystem', 'Configs', 'Configs', 'Configs', '2017-06-16 09:51:14', 1, 1, '0000-00-00 00:00:00', 0),
(6, 'ReservationSystem', 'Sheduleviewer', 'Sheduleviewer', 'Sheduleviewer', '2017-06-21 10:01:55', 1, 1, '0000-00-00 00:00:00', 0),
(7, 'ReservationSystem', 'ReservedUserList', 'ReservedUserList', 'ReservedUserList', '2017-06-21 10:02:11', 1, 1, '0000-00-00 00:00:00', 0),
(8, 'ReservationSystem', 'Setuphours', 'Setuphours', 'Setuphours', '2017-07-04 22:19:53', 1, 1, '0000-00-00 00:00:00', 0),
(9, 'ReservationSystem', 'CancelledReservedList', 'CancelledReservedList', 'CancelledReservedList', '2017-10-23 12:39:35', 1, 1, '0000-00-00 00:00:00', 0),
(10, 'ReservationSystem', 'UploadZipCode', 'UploadZipCode', 'UploadZipCode', '2017-10-23 12:39:54', 1, 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `res_reservation_payment`
--

CREATE TABLE `res_reservation_payment` (
  `reservation_payment_id` int(11) NOT NULL,
  `res_code` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(20) DEFAULT NULL,
  `transaction_amount` varchar(10) DEFAULT NULL,
  `transaction_status` varchar(20) DEFAULT NULL,
  `currency` varchar(6) DEFAULT NULL,
  `custom_message` text,
  `is_refund` tinyint(4) DEFAULT NULL,
  `refund_transaction_id` varchar(20) DEFAULT NULL,
  `fee_refund_amount` varchar(10) DEFAULT NULL,
  `gross_refund_amount` varchar(10) DEFAULT NULL,
  `net_refund_amount` varchar(10) DEFAULT NULL,
  `refund_currency_code` varchar(6) DEFAULT NULL,
  `total_refunded_amount` varchar(10) DEFAULT NULL,
  `refund_timestamp` timestamp NULL DEFAULT NULL,
  `correlation_id` varchar(20) DEFAULT NULL,
  `refund_ack` varchar(20) DEFAULT NULL,
  `refund_build` varchar(20) DEFAULT NULL,
  `refund_status` varchar(20) DEFAULT NULL,
  `pending_reason` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_role_master`
--

CREATE TABLE `res_role_master` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-active,0-inactive',
  `is_delete` int(4) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_role_master`
--

INSERT INTO `res_role_master` (`role_id`, `role_name`, `created_date`, `status`, `is_delete`, `updated_by`, `created_by`, `updated_date`) VALUES
(1, 'Admin', '2017-06-14 00:00:00', 1, 0, 0, 0, '0000-00-00 00:00:00'),
(2, 'Schedule Viewer', '2017-06-01 00:00:00', 1, 0, 0, 1, '0000-00-00 00:00:00'),
(3, 'Gate Keeper 1', '2017-08-03 00:12:04', 1, 0, 0, 1, '0000-00-00 00:00:00'),
(4, 'Gate Keeper 2', '2017-08-03 00:12:41', 1, 0, 0, 1, '0000-00-00 00:00:00'),
(5, 'Reserved User', '2017-08-03 00:13:21', 1, 0, 0, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `res_upload_zip_code`
--

CREATE TABLE `res_upload_zip_code` (
  `id` int(11) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `population` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_user`
--

CREATE TABLE `res_user` (
  `user_id` int(11) NOT NULL COMMENT 'PK',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_type` int(10) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `reset_password_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `res_user`
--

INSERT INTO `res_user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `role_type`, `is_delete`, `status`, `created_at`, `modified_at`, `reset_password_token`) VALUES
(1, 'Mehul', 'Patel', 'mehul.patel@c-metric.com', 'e6e061838856bf47e1de730719fb2609', 1, 0, 1, '2017-08-03 00:00:00', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `res_user_cancel_shedule_time_slot`
--

CREATE TABLE `res_user_cancel_shedule_time_slot` (
  `user_reservation_id` int(11) NOT NULL COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK-res_user',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `weekly_ts_id` int(11) NOT NULL COMMENT 'FK-res_weekly_time_slot',
  `hourly_ts_id` int(11) NOT NULL COMMENT 'FK-res_hourly_time_slot',
  `no_of_group_people` int(11) NOT NULL DEFAULT '0',
  `no_of_people` int(11) NOT NULL,
  `config_no_of_people` int(11) NOT NULL,
  `reservation_code` varchar(50) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `pdf_file_name` varchar(255) NOT NULL,
  `cancellation_code` varchar(50) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_user_shedule_time_slot`
--

CREATE TABLE `res_user_shedule_time_slot` (
  `user_reservation_id` int(11) NOT NULL COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK-res_user',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `weekly_ts_id` int(11) NOT NULL COMMENT 'FK-res_weekly_time_slot',
  `hourly_ts_id` int(11) NOT NULL COMMENT 'FK-res_hourly_time_slot',
  `no_of_group_people` int(11) NOT NULL DEFAULT '0',
  `no_of_people` int(11) NOT NULL,
  `config_no_of_people` int(11) NOT NULL,
  `reservation_code` varchar(50) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `pdf_file_name` varchar(255) NOT NULL,
  `cancellation_code` varchar(50) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_user_visit`
--

CREATE TABLE `res_user_visit` (
  `user_visit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `weekly_ts_id` int(11) NOT NULL,
  `hourly_ts_id` int(11) NOT NULL,
  `first_visit` tinyint(1) NOT NULL,
  `final_visit` tinyint(1) NOT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_reservation_id` int(11) NOT NULL,
  `is_escaped` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_weekly_hourly_total_slot`
--

CREATE TABLE `res_weekly_hourly_total_slot` (
  `total_slot_id` int(11) NOT NULL COMMENT 'PK',
  `total_slot` int(11) NOT NULL,
  `week_start_date` date NOT NULL,
  `week_end_date` date NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `res_weekly_time_slot_reservable`
--

CREATE TABLE `res_weekly_time_slot_reservable` (
  `weekly_ts_id` int(11) NOT NULL COMMENT 'PK',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_open` tinyint(1) NOT NULL COMMENT '1- open,2-close',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `res_aauth_perms`
--
ALTER TABLE `res_aauth_perms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_index` (`id`);

--
-- Indexes for table `res_aauth_perm_to_group`
--
ALTER TABLE `res_aauth_perm_to_group`
  ADD KEY `perm_id_role_id_index` (`perm_id`,`role_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `res_accesscontroloperation`
--
ALTER TABLE `res_accesscontroloperation`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `res_group_reservation`
--
ALTER TABLE `res_group_reservation`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `res_hourly_time_slot`
--
ALTER TABLE `res_hourly_time_slot`
  ADD PRIMARY KEY (`hourly_ts_id`),
  ADD KEY `weekly_ts_id` (`weekly_ts_id`);

--
-- Indexes for table `res_language_master`
--
ALTER TABLE `res_language_master`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `res_module_master`
--
ALTER TABLE `res_module_master`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `res_reservation_payment`
--
ALTER TABLE `res_reservation_payment`
  ADD PRIMARY KEY (`reservation_payment_id`);

--
-- Indexes for table `res_role_master`
--
ALTER TABLE `res_role_master`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `res_upload_zip_code`
--
ALTER TABLE `res_upload_zip_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `res_user`
--
ALTER TABLE `res_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `res_user_cancel_shedule_time_slot`
--
ALTER TABLE `res_user_cancel_shedule_time_slot`
  ADD PRIMARY KEY (`user_reservation_id`),
  ADD KEY `user_id` (`user_id`,`weekly_ts_id`,`hourly_ts_id`),
  ADD KEY `weekly_ts_id` (`weekly_ts_id`),
  ADD KEY `hourly_ts_id` (`hourly_ts_id`);

--
-- Indexes for table `res_user_shedule_time_slot`
--
ALTER TABLE `res_user_shedule_time_slot`
  ADD PRIMARY KEY (`user_reservation_id`),
  ADD KEY `user_id` (`user_id`,`weekly_ts_id`,`hourly_ts_id`),
  ADD KEY `weekly_ts_id` (`weekly_ts_id`),
  ADD KEY `hourly_ts_id` (`hourly_ts_id`);

--
-- Indexes for table `res_user_visit`
--
ALTER TABLE `res_user_visit`
  ADD PRIMARY KEY (`user_visit_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `weekly_ts_id` (`weekly_ts_id`),
  ADD KEY `hourly_ts_id` (`hourly_ts_id`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Indexes for table `res_weekly_hourly_total_slot`
--
ALTER TABLE `res_weekly_hourly_total_slot`
  ADD PRIMARY KEY (`total_slot_id`);

--
-- Indexes for table `res_weekly_time_slot_reservable`
--
ALTER TABLE `res_weekly_time_slot_reservable`
  ADD PRIMARY KEY (`weekly_ts_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `res_aauth_perms`
--
ALTER TABLE `res_aauth_perms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `res_group_reservation`
--
ALTER TABLE `res_group_reservation`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `res_hourly_time_slot`
--
ALTER TABLE `res_hourly_time_slot`
  MODIFY `hourly_ts_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK';
--
-- AUTO_INCREMENT for table `res_language_master`
--
ALTER TABLE `res_language_master`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `res_module_master`
--
ALTER TABLE `res_module_master`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `res_role_master`
--
ALTER TABLE `res_role_master`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `res_upload_zip_code`
--
ALTER TABLE `res_upload_zip_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `res_user`
--
ALTER TABLE `res_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `res_user_cancel_shedule_time_slot`
--
ALTER TABLE `res_user_cancel_shedule_time_slot`
  MODIFY `user_reservation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK';
--
-- AUTO_INCREMENT for table `res_user_shedule_time_slot`
--
ALTER TABLE `res_user_shedule_time_slot`
  MODIFY `user_reservation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK';
--
-- AUTO_INCREMENT for table `res_user_visit`
--
ALTER TABLE `res_user_visit`
  MODIFY `user_visit_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `res_weekly_hourly_total_slot`
--
ALTER TABLE `res_weekly_hourly_total_slot`
  MODIFY `total_slot_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK';
--
-- AUTO_INCREMENT for table `res_weekly_time_slot_reservable`
--
ALTER TABLE `res_weekly_time_slot_reservable`
  MODIFY `weekly_ts_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK';
--
-- Constraints for dumped tables
--

--
-- Constraints for table `res_aauth_perm_to_group`
--
ALTER TABLE `res_aauth_perm_to_group`
  ADD CONSTRAINT `res_aauth_perm_to_group_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `res_role_master` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `res_aauth_perm_to_group_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `res_module_master` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `res_aauth_perm_to_group_ibfk_3` FOREIGN KEY (`perm_id`) REFERENCES `res_aauth_perms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `res_group_reservation`
--
ALTER TABLE `res_group_reservation`
  ADD CONSTRAINT `res_group_user` FOREIGN KEY (`user_id`) REFERENCES `res_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `res_hourly_time_slot`
--
ALTER TABLE `res_hourly_time_slot`
  ADD CONSTRAINT `res_hourly_time_slot_ibfk_1` FOREIGN KEY (`weekly_ts_id`) REFERENCES `res_weekly_time_slot_reservable` (`weekly_ts_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `res_user_shedule_time_slot`
--
ALTER TABLE `res_user_shedule_time_slot`
  ADD CONSTRAINT `res_user_shedule_time_slot_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `res_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `res_user_shedule_time_slot_ibfk_2` FOREIGN KEY (`weekly_ts_id`) REFERENCES `res_weekly_time_slot_reservable` (`weekly_ts_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `res_user_shedule_time_slot_ibfk_3` FOREIGN KEY (`hourly_ts_id`) REFERENCES `res_hourly_time_slot` (`hourly_ts_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `res_user_visit`
--
ALTER TABLE `res_user_visit`
  ADD CONSTRAINT `res_user_con_1` FOREIGN KEY (`user_id`) REFERENCES `res_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `res_user_con_2` FOREIGN KEY (`weekly_ts_id`) REFERENCES `res_weekly_time_slot_reservable` (`weekly_ts_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `res_user_con_3` FOREIGN KEY (`hourly_ts_id`) REFERENCES `res_hourly_time_slot` (`hourly_ts_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
