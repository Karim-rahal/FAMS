-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 12:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fams`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `present_count` int(11) DEFAULT 0,
  `absent_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `session_id`, `present_count`, `absent_count`) VALUES
(1, 2, 21, 1, 0),
(2, 2, 22, 1, 0),
(4, 2, 24, 2, 0),
(5, 2, 25, 1, 0),
(7, 2, 27, 1, 0),
(8, 2, 28, 1, 0),
(10, 2, 30, 1, 0),
(11, 2, 31, 1, 0),
(13, 2, 33, 1, 0),
(14, 2, 34, 1, 0),
(16, 2, 36, 1, 0),
(17, 2, 37, 1, 0),
(19, 2, 39, 1, 0),
(20, 2, 40, 1, 0),
(22, 7, 24, 1, 0),
(23, 3, 22, 1, 0),
(24, 3, 25, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coaching_materials`
--

CREATE TABLE `coaching_materials` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `team` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coaching_materials`
--

INSERT INTO `coaching_materials` (`id`, `coach_id`, `team`, `file_path`, `uploaded_at`) VALUES
(1, 5, 'U21', '../uploads/1745363271_Sample-CVs (Autosaved).docx', '2025-04-22 23:07:51');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('Available','In Use','Damaged','Unavailable') DEFAULT 'Available',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `name`, `type`, `quantity`, `status`, `updated_at`) VALUES
(1, 'Football', 'Ball', 30, 'Available', '2025-04-21 18:58:16'),
(2, 'Goalkeeper Gloves', 'Protective Gear', 15, 'Available', '2025-04-21 18:58:16'),
(3, 'Training Bibs', 'Clothing', 40, 'Available', '2025-04-21 18:58:16'),
(4, 'Cones', 'Training Aid', 100, 'Available', '2025-04-21 18:58:16'),
(5, 'Speed Ladders', 'Agility Equipment', 10, 'Available', '2025-04-21 18:58:16'),
(6, 'Whistles', 'Coach Tool', 5, 'Available', '2025-04-21 18:58:16'),
(7, 'First Aid Kit', 'Medical', 3, 'Available', '2025-04-21 18:58:16'),
(8, 'Goal Nets', 'Field Equipment', 4, 'In Use', '2025-04-21 18:58:16'),
(9, 'Water Bottles', 'Hydration', 44, 'Available', '2025-04-21 19:01:10'),
(10, 'Resistance Bands', 'Strength Training', 20, 'Available', '2025-04-21 18:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `match_date` date NOT NULL,
  `opponent` varchar(100) NOT NULL,
  `location` varchar(150) NOT NULL,
  `team` enum('U15','U18','U21') DEFAULT 'U15',
  `status` enum('Scheduled','Completed','Canceled') NOT NULL DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `match_date`, `opponent`, `location`, `team`, `status`) VALUES
(1, '2025-04-21', 'Tigers FC', 'Main Stadium', 'U15', 'Scheduled'),
(2, '2025-04-29', 'Tigers FC', 'Main Stadium', 'U21', 'Scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_role` enum('Admin','Coach') NOT NULL,
  `target_role` enum('Player','Coach') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `target_team` enum('U15','U18','U21','ALL') DEFAULT 'ALL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `sender_id`, `sender_role`, `target_role`, `created_at`, `target_team`) VALUES
(1, 'Payments', 'For all Players the deadline of payments is by April 23.', 1, 'Admin', 'Player', '2025-04-21 22:27:21', 'ALL'),
(5, 'Payments', 'Deadline April 23.', 1, 'Admin', 'Player', '2025-04-21 22:32:12', 'ALL'),
(6, 'Matches are canceled this week', 'Matches are canceled this week.', 1, 'Admin', 'Coach', '2025-04-21 22:32:57', 'ALL'),
(7, 'No Training', 'No Training This week', 5, 'Coach', 'Player', '2025-04-21 22:40:01', 'U21'),
(9, 'Notify', 'Here is a message from admin to all players', 1, 'Admin', 'Player', '2025-04-23 02:00:51', 'ALL');

-- --------------------------------------------------------

--
-- Table structure for table `notification_reads`
--

CREATE TABLE `notification_reads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_reads`
--

INSERT INTO `notification_reads` (`id`, `user_id`, `notification_id`, `is_read`, `read_at`) VALUES
(1, 2, 1, 1, '2025-04-21 22:33:22'),
(2, 3, 1, 1, '2025-04-21 22:33:53'),
(3, 7, 1, 0, NULL),
(4, 2, 2, 1, '2025-04-21 22:33:25'),
(5, 3, 2, 1, '2025-04-21 22:33:58'),
(6, 7, 2, 0, NULL),
(7, 2, 3, 1, '2025-04-21 22:33:27'),
(8, 3, 3, 1, '2025-04-21 22:33:57'),
(9, 7, 3, 0, NULL),
(10, 2, 4, 1, '2025-04-21 22:33:29'),
(11, 3, 4, 1, '2025-04-21 22:33:56'),
(12, 7, 4, 0, NULL),
(13, 2, 5, 1, '2025-04-21 22:33:31'),
(14, 3, 5, 1, '2025-04-21 22:33:54'),
(15, 7, 5, 0, NULL),
(16, 4, 6, 0, NULL),
(17, 5, 6, 1, '2025-04-21 22:34:18'),
(18, 6, 6, 0, NULL),
(19, 2, 7, 1, '2025-04-21 22:43:46'),
(20, 2, 8, 1, '2025-04-21 22:43:48'),
(21, 2, 9, 1, '2025-04-23 02:01:22'),
(22, 3, 9, 1, '2025-04-23 02:01:07'),
(23, 7, 9, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `due` decimal(10,2) NOT NULL,
  `status` enum('Pending','Paid') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `due`, `status`) VALUES
(7, 2, 60.00, 'Paid'),
(9, 7, 30.00, 'Paid'),
(12, 3, 45.00, 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `player_progress`
--

CREATE TABLE `player_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `skill_area` enum('Dribbling','Passing','Shooting','Stamina','Tactical','Discipline') NOT NULL,
  `rating` tinyint(4) DEFAULT NULL CHECK (`rating` between 1 and 10),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_progress`
--

INSERT INTO `player_progress` (`id`, `user_id`, `session_id`, `date`, `skill_area`, `rating`, `notes`) VALUES
(47, 7, 27, '2024-04-07', 'Stamina', 9, 'Solid work but needs improvement in focus'),
(48, 7, 33, '2024-04-13', 'Shooting', 8, 'Excellent effort and discipline'),
(49, 3, 40, '2024-04-20', 'Shooting', 6, 'Passive involvement, needs more intensity'),
(50, 2, 50, '2024-05-07', 'Shooting', 9, 'Excellent effort and discipline');

-- --------------------------------------------------------

--
-- Table structure for table `session_types`
--

CREATE TABLE `session_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `team` enum('U15','U18','U21','NONE') DEFAULT 'NONE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session_types`
--

INSERT INTO `session_types` (`id`, `name`, `team`) VALUES
(1, 'Dribbling Basics', 'U15'),
(2, 'Shooting Practice', 'U15'),
(3, 'Small-sided Games', 'U15'),
(4, 'Defensive Shape', 'U15'),
(5, 'Passing Drills', 'U15'),
(6, 'Basic Ball Control', 'U15'),
(7, '1v1 Challenges', 'U15'),
(8, 'Team Coordination', 'U15'),
(9, 'Warm-up & Cool-down', 'U15'),
(10, 'Intro to Positioning', 'U15'),
(11, 'Tactical Pressing', 'U18'),
(12, 'Video Analysis', 'U18'),
(13, 'Set Piece Execution', 'U18'),
(14, 'Match Preparation', 'U18'),
(15, 'Advanced Drills', 'U18'),
(16, 'Midfield Transitions', 'U18'),
(17, 'Finishing Under Pressure', 'U18'),
(18, 'Defensive Resilience', 'U18'),
(19, 'Game Simulation', 'U18'),
(20, 'Counterattack Patterns', 'U18'),
(26, 'Injury Prevention & Rehab', 'U21'),
(31, 'Warm-Up & Mobility', 'U21'),
(32, 'Technical Drills', 'U21'),
(33, 'Tactical Training', 'U21'),
(34, 'Small-Sided Games', 'U21'),
(35, 'Conditioning & Endurance', 'U21'),
(36, 'Set Pieces & Finishing', 'U21');

-- --------------------------------------------------------

--
-- Table structure for table `training_attendance`
--

CREATE TABLE `training_attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `status` enum('Present','Absent') DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_sessions`
--

CREATE TABLE `training_sessions` (
  `id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `session_type` varchar(100) NOT NULL,
  `team` enum('U15','U18','U21','NONE') DEFAULT 'NONE',
  `status` enum('Scheduled','In Progress','Completed') NOT NULL DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_sessions`
--

INSERT INTO `training_sessions` (`id`, `session_date`, `session_type`, `team`, `status`) VALUES
(21, '2024-04-01', 'Basic Dribbling Drills', 'U15', 'Completed'),
(22, '2024-04-02', 'Defensive Positioning', 'U18', 'Completed'),
(24, '2024-04-04', 'Small-Sided Games', 'U15', 'Scheduled'),
(25, '2024-04-05', 'Set Piece Defense', 'U18', 'Scheduled'),
(27, '2024-04-07', '1v1 Attacking Skills', 'U15', 'Scheduled'),
(28, '2024-04-08', 'Counterattack Drills', 'U18', 'Scheduled'),
(30, '2024-04-10', 'Passing Combinations', 'U15', 'Scheduled'),
(31, '2024-04-11', 'Zonal Marking', 'U18', 'Scheduled'),
(33, '2024-04-13', 'Crossing and Finishing', 'U15', 'Scheduled'),
(34, '2024-04-14', 'Midfield Control Drills', 'U18', 'Scheduled'),
(36, '2024-04-16', 'Agility Circuit', 'U15', 'Scheduled'),
(37, '2024-04-17', 'Transition Play Training', 'U18', 'Scheduled'),
(39, '2024-04-19', 'First Touch & Ball Reception', 'U15', 'Scheduled'),
(40, '2024-04-20', 'High Pressing Tactics', 'U18', 'Scheduled'),
(41, '2025-04-30', '1v1 Challenges', 'U15', 'Scheduled'),
(42, '2024-04-22', 'High-Intensity Interval Training', 'U21', 'Scheduled'),
(43, '2024-04-23', 'Tactical Transitions & Build-Up', 'U21', 'Scheduled'),
(44, '2024-04-25', 'Defensive Structure in Midfield Press', 'U21', 'Scheduled'),
(45, '2024-04-27', 'Attacking Patterns & Width Play', 'U21', 'Scheduled'),
(46, '2024-04-29', 'Set-Piece Strategy & Execution', 'U21', 'Scheduled'),
(47, '2024-05-01', 'Small-Sided Positional Games', 'U21', 'Scheduled'),
(48, '2024-05-03', 'Fitness Benchmark Testing', 'U21', 'Scheduled'),
(49, '2024-05-05', 'Video Analysis: Game Review', 'U21', 'Scheduled'),
(50, '2024-05-07', 'Finishing Drills in Final Third', 'U21', 'Scheduled'),
(51, '2024-05-09', 'High Press Simulation & Triggers', 'U21', 'Scheduled'),
(52, '2025-04-30', 'Tactical Pressing', 'U18', 'Scheduled'),
(53, '2025-04-30', 'Injury Prevention & Rehab', 'U21', 'Scheduled'),
(54, '2025-04-30', 'Injury Prevention & Rehab', 'U21', 'Scheduled'),
(55, '2025-05-14', 'Set Pieces & Finishing', 'U21', 'Scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `age` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('NONE','Admin','Player','Coach') NOT NULL DEFAULT 'NONE',
  `plan` enum('U15','U18','U21','NONE') DEFAULT 'NONE',
  `total_attended` int(11) DEFAULT 0,
  `total_absent` int(11) DEFAULT 0,
  `coach_type` enum('Head Coach','Assistant Coach','Goalkeeping Coach','Fitness Coach') DEFAULT NULL,
  `coach_team` enum('U15','U18','U21') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `father_name`, `gender`, `age`, `email`, `password_hash`, `created_at`, `role`, `plan`, `total_attended`, `total_absent`, `coach_type`, `coach_team`) VALUES
(1, 'Karim Rahal', 'Hussien', 'Male', 20, 'karim.rahal.2017@gmail.com', '$2y$10$piYVxTqNbCSYeK2OtT/.B.jqobZruQRVHijDldnjXQLcgFFcgowE.', '2025-04-20 22:53:50', 'Admin', '', 0, 0, NULL, NULL),
(2, 'Sarah Dhainy', 'Hussein', 'Female', 20, 'sdhainy36@gmail.com', '$2y$10$7kjTsADsAgqJYSGt8paEnOjby3a.P20r3QOuvyUFHXOtwtOsXffhm', '2025-04-21 02:31:54', 'Player', 'U21', 1, 0, NULL, NULL),
(3, 'Zein AlAbidin', 'Adnan', 'Male', 21, 'Zein@gmail.com', '$2y$10$Zxy2hWZosy9uNVrzxyQa.ulLzw8Oo0upeu68S.cirPcwp6jKZdhpm', '2025-04-21 08:03:55', 'Player', 'U18', 1, 1, NULL, NULL),
(4, 'Hasan rahal', 'Hussein', 'Male', 26, 'hasan@gmail.com', '$2y$10$ZuUXj8qaUcoIZTvrURQAu.kUglpAnySykvwLXj3xzZhjxnIwlqYRu', '2025-04-21 15:47:34', 'Coach', 'NONE', 0, 0, 'Head Coach', 'U15'),
(5, 'Ossama', 'Rahal', 'Male', 30, 'Ossama@gmail.com', '$2y$10$7oEQ1LI5hw3XY5Qi/XWFPOqlDDAAOzYw7hvsVlmzPwV/ZHmSwWk8i', '2025-04-21 15:48:11', 'Coach', 'NONE', 0, 0, 'Head Coach', 'U21'),
(6, 'Ali AbuTarraf', 'Kamal', 'Male', 25, 'AliAbutarraf@gmail.com', '$2y$10$WtNJ6f730iO867qPjr76cOkSZdLv5TgXra3TYP9nlGiTBX/e38a/2', '2025-04-21 16:17:55', 'Coach', 'NONE', 0, 0, 'Head Coach', 'U18'),
(7, 'Nazih Chahine', 'Abed', 'Male', 20, 'nazih@gmail.com', '$2y$10$TA1.iiYkewoMntYcLYrNxeNcXHqjdAOkCsabfSBcLudvWREm3MvM.', '2025-04-21 16:27:13', 'Player', 'U15', 1, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`user_id`,`session_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `coaching_materials`
--
ALTER TABLE `coaching_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_reads`
--
ALTER TABLE `notification_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_read` (`user_id`,`notification_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `player_progress`
--
ALTER TABLE `player_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `session_types`
--
ALTER TABLE `session_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_attendance`
--
ALTER TABLE `training_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `coaching_materials`
--
ALTER TABLE `coaching_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notification_reads`
--
ALTER TABLE `notification_reads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `player_progress`
--
ALTER TABLE `player_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `session_types`
--
ALTER TABLE `session_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `training_attendance`
--
ALTER TABLE `training_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `training_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coaching_materials`
--
ALTER TABLE `coaching_materials`
  ADD CONSTRAINT `coaching_materials_ibfk_1` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `player_progress`
--
ALTER TABLE `player_progress`
  ADD CONSTRAINT `player_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `player_progress_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `training_sessions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `training_attendance`
--
ALTER TABLE `training_attendance`
  ADD CONSTRAINT `training_attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_attendance_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `training_sessions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
