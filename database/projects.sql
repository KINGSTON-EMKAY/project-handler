-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2021 at 01:40 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projects`
--

-- --------------------------------------------------------

--
-- Table structure for table `cost_definition`
--

CREATE TABLE `cost_definition` (
  `defined_cost_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `cost_name` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `delete_status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cost_definition`
--

INSERT INTO `cost_definition` (`defined_cost_id`, `project_id`, `cost_name`, `date_created`, `delete_status`) VALUES
(45, 62, 'Bee Hives', '0000-00-00', '1'),
(46, 62, 'Sugar', '0000-00-00', '1'),
(47, 62, 'Buckets', '0000-00-00', '1'),
(48, 62, 'Transport', '0000-00-00', '1'),
(49, 63, 'heating and lighting', '0000-00-00', '1'),
(50, 63, 'Bedding', '0000-00-00', '1'),
(51, 63, 'Feed', '0000-00-00', '1'),
(52, 63, 'Chemicals', '0000-00-00', '1'),
(53, 63, 'Labour', '0000-00-00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `incurred_costs`
--

CREATE TABLE `incurred_costs` (
  `incurred_cost_id` int(11) NOT NULL,
  `supplier_details` varchar(255) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `defined_cost_id` int(11) NOT NULL,
  `cost_description` varchar(255) NOT NULL,
  `cost_value` decimal(30,0) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `date_incurred` date NOT NULL,
  `delete_status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `incurred_costs`
--

INSERT INTO `incurred_costs` (`incurred_cost_id`, `supplier_details`, `batch_id`, `defined_cost_id`, `cost_description`, `cost_value`, `invoice_number`, `date_incurred`, `delete_status`) VALUES
(80, 'Kingston 0775779878', 21, 45, 'Purchase of 20 bee hives ($25x20 units)', '500', '0000', '2021-10-06', '1');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sys_id` int(200) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_description` varchar(300) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `delete_status` enum('1','2','3') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `user_id`, `sys_id`, `project_name`, `project_description`, `date_created`, `delete_status`) VALUES
(51, 2, 78798, 'Peas', '2 Hectares of peas (Winter season)', '2021-09-23 08:33:09', '1'),
(62, 3, 18738, 'Bee Keeping', '20 Bee hives on a plot', '2021-10-06 12:07:43', '1'),
(63, 3, 18738, 'Poultry', 'Broilers. 100 a batch 3 week spacing', '2021-10-06 12:09:52', '1');

-- --------------------------------------------------------

--
-- Table structure for table `project_batch`
--

CREATE TABLE `project_batch` (
  `batch_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `batch_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `completion_status` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '! is for completed, 2 is for not completed, 3 is for failed(future use)',
  `delete_status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 is for undeleted, 2 deleted, 3 (future use)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project_batch`
--

INSERT INTO `project_batch` (`batch_id`, `project_id`, `batch_name`, `start_date`, `end_date`, `completion_status`, `delete_status`) VALUES
(21, 62, 'Bees 01', '2021-10-06', '2021-12-06', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `received_revenue`
--

CREATE TABLE `received_revenue` (
  `received_revenue_id` int(11) NOT NULL,
  `client_details` varchar(255) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `defined_revenue_id` int(11) NOT NULL,
  `revenue_description` varchar(255) NOT NULL,
  `revenue_value` decimal(10,0) NOT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `date_received` date NOT NULL,
  `delete_status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `received_revenue`
--

INSERT INTO `received_revenue` (`received_revenue_id`, `client_details`, `batch_id`, `defined_revenue_id`, `revenue_description`, `revenue_value`, `receipt_number`, `date_received`, `delete_status`) VALUES
(19, 'KingCustomer 077', 21, 22, 'Sale of 600 bee nuclei', '600', '0000', '2021-10-30', '1'),
(20, 'KingCustomer 077', 21, 22, 'Sale of 400 bee nuclei', '400', '0000', '2021-10-30', '1'),
(21, 'KingCustomer 077', 21, 20, '200 litres of wax', '1000', '0001', '2021-11-06', '1');

-- --------------------------------------------------------

--
-- Table structure for table `revenue_definition`
--

CREATE TABLE `revenue_definition` (
  `defined_revenue_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `revenue_name` varchar(255) NOT NULL,
  `delete_status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `revenue_definition`
--

INSERT INTO `revenue_definition` (`defined_revenue_id`, `project_id`, `revenue_name`, `delete_status`) VALUES
(20, 62, 'Wax', '1'),
(21, 62, 'Honey', '1'),
(22, 62, 'Nuclei', '1'),
(23, 63, 'Manure', '1'),
(24, 63, 'Chicken Cuts', '1'),
(25, 63, 'live birds', '1');

-- --------------------------------------------------------

--
-- Table structure for table `system_users`
--

CREATE TABLE `system_users` (
  `user_id` int(11) NOT NULL,
  `sys_id` int(200) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` enum('1','2','3') NOT NULL DEFAULT '1',
  `verification` enum('1','2','3') NOT NULL DEFAULT '2',
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  `delete_status` enum('1','2','3') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_users`
--

INSERT INTO `system_users` (`user_id`, `sys_id`, `firstname`, `lastname`, `email`, `password`, `user_role`, `verification`, `registration_date`, `delete_status`) VALUES
(1, 10929, 'Abe', 'mwanza', 'abemwanza@gmail.com', '00000000', '1', '2', '2021-07-21 19:10:04', '1'),
(2, 78798, 'Kingston', 'mwanza', 'tkingston02@gmail.com', '00000000', '2', '2', '2021-07-21 19:13:17', '1'),
(3, 18738, 'Eleanor', 'Mwanza', 'el@gmail.com', '00000000', '1', '2', '2021-07-21 19:20:11', '1'),
(4, 78631, 'Tonderai', 'Tsaurai', 'tondetsaurai@gmail.com', '00000000', '1', '2', '2021-07-24 18:38:20', '1'),
(11, 199683, 'Test', 'Test', 'test@gmail.com', '00000000', '1', '2', '2021-10-06 08:47:11', '1'),
(12, 184400, 'Norest', 'Mwanza', 'nm@gmail.com', '00000000', '1', '2', '2021-10-06 08:49:00', '1'),
(13, 439179, 'Zviko', 'Nyambiri', 'zn@gmail.com', '00000000', '1', '2', '2021-10-06 08:50:57', '1'),
(14, 24547, 'Kingston', 'Mwanza', 'km@gmail.com', '00000000', '1', '2', '2021-10-06 08:59:13', '1'),
(15, 390522, 'Test', 'Tet', 'test1@gmail.com', '00000000', '1', '2', '2021-10-06 09:29:05', '1'),
(16, 439179, 'Test', 'Test', 'add@gmail.com', '11111111', '2', '2', '2021-10-06 09:31:01', '1'),
(17, 704451, 'Abe', 'Mwanza', 'abe@gmail.com', '00000000', '1', '2', '2021-10-06 09:32:18', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cost_definition`
--
ALTER TABLE `cost_definition`
  ADD PRIMARY KEY (`defined_cost_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `incurred_costs`
--
ALTER TABLE `incurred_costs`
  ADD PRIMARY KEY (`incurred_cost_id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `cost_id` (`defined_cost_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sys_id` (`sys_id`);

--
-- Indexes for table `project_batch`
--
ALTER TABLE `project_batch`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `received_revenue`
--
ALTER TABLE `received_revenue`
  ADD PRIMARY KEY (`received_revenue_id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `revenue_id` (`defined_revenue_id`);

--
-- Indexes for table `revenue_definition`
--
ALTER TABLE `revenue_definition`
  ADD PRIMARY KEY (`defined_revenue_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `system_users`
--
ALTER TABLE `system_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `firstname` (`firstname`,`lastname`,`email`),
  ADD KEY `sys_id` (`sys_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cost_definition`
--
ALTER TABLE `cost_definition`
  MODIFY `defined_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `incurred_costs`
--
ALTER TABLE `incurred_costs`
  MODIFY `incurred_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `project_batch`
--
ALTER TABLE `project_batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `received_revenue`
--
ALTER TABLE `received_revenue`
  MODIFY `received_revenue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `revenue_definition`
--
ALTER TABLE `revenue_definition`
  MODIFY `defined_revenue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `system_users`
--
ALTER TABLE `system_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cost_definition`
--
ALTER TABLE `cost_definition`
  ADD CONSTRAINT `cost_definition_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incurred_costs`
--
ALTER TABLE `incurred_costs`
  ADD CONSTRAINT `incurred_costs_ibfk_1` FOREIGN KEY (`defined_cost_id`) REFERENCES `cost_definition` (`defined_cost_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incurred_costs_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `project_batch` (`batch_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `system_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`sys_id`) REFERENCES `system_users` (`sys_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_batch`
--
ALTER TABLE `project_batch`
  ADD CONSTRAINT `project_batch_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `received_revenue`
--
ALTER TABLE `received_revenue`
  ADD CONSTRAINT `received_revenue_ibfk_2` FOREIGN KEY (`defined_revenue_id`) REFERENCES `revenue_definition` (`defined_revenue_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `received_revenue_ibfk_3` FOREIGN KEY (`batch_id`) REFERENCES `project_batch` (`batch_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `revenue_definition`
--
ALTER TABLE `revenue_definition`
  ADD CONSTRAINT `revenue_definition_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
