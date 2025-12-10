-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 01:36 PM
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
-- Database: `attendancesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `StudentScheduleID` int(11) DEFAULT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `SubjectID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `TimeIn` time DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `StudentScheduleID`, `StudentID`, `SubjectID`, `Date`, `TimeIn`, `Status`) VALUES
(2, 11, 3, 3, '2025-12-07', '19:40:03', 'Late'),
(3, 11, 3, 3, '2025-12-10', '19:36:00', 'Late'),
(4, 127, 21, 7, '2025-12-10', '19:17:23', 'Late'),
(5, 129, 21, 9, '2025-12-10', '19:17:35', 'Late'),
(6, 126, 21, 6, '2025-12-10', '19:17:54', 'Late'),
(7, 130, 21, 10, '2025-12-10', '19:17:55', 'Late'),
(8, 125, 21, 5, '2025-12-10', '19:17:57', 'Late'),
(9, 19, 3, 7, '2025-12-10', '19:36:09', 'Late'),
(10, NULL, 3, 8, '2025-12-10', '19:39:42', 'Late'),
(11, 342, 3, 8, '2025-12-10', '19:46:01', 'Present'),
(12, 343, 57, 13, '2025-12-10', '19:44:30', 'Present'),
(13, 18, 3, 6, '2025-12-10', '19:46:03', 'Late');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `DepartmentID` int(11) NOT NULL,
  `DepartmentName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`DepartmentID`, `DepartmentName`) VALUES
(1, 'SIT'),
(2, 'SON'),
(4, 'SIHTM'),
(5, 'SOD'),
(6, 'SCJPS');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `RoomID` int(11) NOT NULL,
  `RoomName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `RoomName`) VALUES
(1, 'F212'),
(2, 'F309'),
(3, 'F217'),
(4, 'F209'),
(5, 'F207'),
(6, 'F214'),
(7, 'F211'),
(8, 'F308'),
(9, 'F307'),
(10, 'F306'),
(11, 'F305');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `ID` int(11) NOT NULL,
  `StudentNumber` varchar(50) DEFAULT NULL,
  `DepartmentID` int(11) DEFAULT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Suffix` varchar(10) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Sex` varchar(10) DEFAULT NULL,
  `YearLevel` int(11) DEFAULT NULL,
  `HouseNo` varchar(20) DEFAULT NULL,
  `Street` varchar(100) DEFAULT NULL,
  `Barangay` varchar(100) DEFAULT NULL,
  `Municipality` varchar(100) DEFAULT NULL,
  `Province` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `ZIPCode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`ID`, `StudentNumber`, `DepartmentID`, `FirstName`, `MiddleName`, `LastName`, `Suffix`, `DateOfBirth`, `Sex`, `YearLevel`, `HouseNo`, `Street`, `Barangay`, `Municipality`, `Province`, `Country`, `ZIPCode`) VALUES
(3, '20232551', 1, 'Kimberly', 'De Vicente', 'Tandoc', '', '2009-01-07', 'Female', 3, '126', 'New Lucban Extension', '', 'Baguio', 'Benguet', 'Philippines', '2100'),
(4, '20143306', 1, 'Calvin', 'Find', 'Janapin', '', '2011-12-09', 'Male', 3, '126', 'New Lucban Extension', '', 'Baguio', 'Benguet', 'Philippines', '2100'),
(5, '20183150', 1, 'Jan', 'Danesse', 'Tanelon', 'Jr.', '1999-12-07', 'Female', 3, '126', 'New Lucban Extension', '', 'Baguio', 'Benguet', 'Philippines', '2100'),
(6, '20232672', 1, 'First', 'Middle', 'LastName', '', '1999-12-07', 'Male', 1, '126', 'New Lucban Extension', '', 'Baguio', 'Benguet', 'Philippines', '2100'),
(7, '20240001', 1, 'Juan', 'Dela', 'Cruz', NULL, '2004-05-15', 'Male', 1, '12A', 'Maple St', 'Camp 7', 'Baguio', 'Benguet', 'Philippines', '2600'),
(8, '20240002', 1, 'Maria', 'Santos', 'Reyes', NULL, '2004-08-20', 'Female', 1, '45', 'Oak Ave', 'Irisan', 'Baguio', 'Benguet', 'Philippines', '2600'),
(9, '20240003', 2, 'Jose', 'Lim', 'Garcia', NULL, '2003-11-01', 'Male', 2, '78B', 'Pine Road', 'San Vicente', 'Manila', 'NCR', 'Philippines', '1000'),
(10, '20240004', 1, 'Anna', 'Lopez', 'Diaz', NULL, '2005-01-25', 'Female', 1, '10', 'Sampaguita St', 'Lower QM', 'Baguio', 'Benguet', 'Philippines', '2600'),
(11, '20240005', 1, 'Luis', 'Gomez', 'Fernandez', NULL, '2003-07-10', 'Male', 2, '22', 'Acacia Lane', 'T. Alonzo', 'Baguio', 'Benguet', 'Philippines', '2600'),
(12, '20240006', 2, 'Sofia', 'Torres', 'Perez', NULL, '2002-09-03', 'Female', 3, '31C', 'Narram St', 'Cubao', 'Quezon City', 'NCR', 'Philippines', '1109'),
(13, '20240007', 1, 'Miguel', 'De Guzman', 'Gonzales', 'Jr.', '2005-02-14', 'Male', 1, '47', 'Mahogany St', 'Slaughter', 'Baguio', 'Benguet', 'Philippines', '2600'),
(14, '20240008', 1, 'Chloe', 'Vargas', 'Alonzo', NULL, '2004-12-05', 'Female', 2, '88', 'Yakal Ave', 'Camp 8', 'Baguio', 'Benguet', 'Philippines', '2600'),
(15, '20240009', 2, 'Ethan', 'Ramos', 'Bautista', NULL, '2003-03-29', 'Male', 3, '15D', 'Tindalo St', 'Ermita', 'Manila', 'NCR', 'Philippines', '1000'),
(16, '20240010', 1, 'Olivia', 'Cruz', 'Santiago', NULL, '2005-10-18', 'Female', 1, '50', 'Tamarind Rd', 'Pacdal', 'Baguio', 'Benguet', 'Philippines', '2600'),
(17, '20240011', 1, 'Noah', 'Dizon', 'Villanueva', NULL, '2002-04-07', 'Male', 3, '99', 'Balete Dr', 'Aurora Hill', 'Baguio', 'Benguet', 'Philippines', '2600'),
(18, '20240012', 2, 'Isabella', 'Mercado', 'Tolentino', NULL, '2004-06-22', 'Female', 2, '62', 'Guava St', 'Poblacion', 'Makati', 'NCR', 'Philippines', '1200'),
(19, '20240013', 1, 'Liam', 'Alcantara', 'Santos', 'III', '2005-09-11', 'Male', 1, '11', 'Palm Ave', 'Loakan', 'Baguio', 'Benguet', 'Philippines', '2600'),
(20, '20240014', 1, 'Mia', 'Montoya', 'Castro', NULL, '2003-02-01', 'Female', 3, '33A', 'Bamboo St', 'Legarda', 'Baguio', 'Benguet', 'Philippines', '2600'),
(21, '20240015', 2, 'Lucas', 'Rizal', 'Marquez', NULL, '2002-10-30', 'Male', 4, '8', 'Lipa St', 'Malate', 'Manila', 'NCR', 'Philippines', '1004'),
(22, '20240016', 1, 'Evelyn', 'Samson', 'Lim', NULL, '2004-03-19', 'Female', 2, '55', 'Orchid Rd', 'Bakakeng', 'Baguio', 'Benguet', 'Philippines', '2600'),
(23, '20240017', 1, 'Alexander', 'Flores', 'Uy', NULL, '2005-06-28', 'Male', 1, '70', 'Bougainvillea St', 'Navy Base', 'Baguio', 'Benguet', 'Philippines', '2600'),
(24, '20240018', 2, 'Harper', 'Dela Cruz', 'Roxas', NULL, '2003-08-07', 'Female', 3, '92', 'Rose St', 'Taft Ave', 'Manila', 'NCR', 'Philippines', '1000'),
(25, '20240019', 1, 'Elijah', 'Medina', 'Tan', NULL, '2004-01-12', 'Male', 2, '18D', 'Jasmine Ct', 'Cabinet Hill', 'Baguio', 'Benguet', 'Philippines', '2600'),
(26, '20240020', 1, 'Abigail', 'Ramos', 'Guzman', NULL, '2005-04-04', 'Female', 1, '64', 'Tulip St', 'Kias', 'Baguio', 'Benguet', 'Philippines', '2600'),
(27, '20240021', 2, 'James', 'Velasco', 'Chua', NULL, '2002-05-27', 'Male', 4, '21', 'Dahlia Ave', 'Barangka', 'Marikina', 'NCR', 'Philippines', '1803'),
(28, '20240022', 1, 'Charlotte', 'Villamor', 'Sy', NULL, '2003-10-09', 'Female', 2, '39', 'Waling-Waling St', 'Trancoville', 'Baguio', 'Benguet', 'Philippines', '2600'),
(29, '20240023', 1, 'Benjamin', 'Ocampo', 'David', NULL, '2005-03-08', 'Male', 1, '5A', 'Carnation Rd', 'Engineer\'s Hill', 'Baguio', 'Benguet', 'Philippines', '2600'),
(30, '20240024', 2, 'Amelia', 'Tolentino', 'Corpuz', NULL, '2004-07-17', 'Female', 2, '77', 'Zinnia St', 'Pio Del Pilar', 'Makati', 'NCR', 'Philippines', '1230'),
(31, '20240025', 1, 'Henry', 'Castro', 'Aquino', NULL, '2002-12-24', 'Male', 3, '101', 'Sunflower Ln', 'Pinsao', 'Baguio', 'Benguet', 'Philippines', '2600'),
(32, '20240026', 1, 'Elizabeth', 'Guevarra', 'Lim', NULL, '2005-09-02', 'Female', 1, '110', 'Iris St', 'Quezon Hill', 'Baguio', 'Benguet', 'Philippines', '2600'),
(33, '20240027', 2, 'Daniel', 'Rivera', 'Soriano', NULL, '2003-01-06', 'Male', 3, '40B', 'Marigold St', 'Maligaya', 'Caloocan', 'NCR', 'Philippines', '1400'),
(34, '20240028', 1, 'Ella', 'Domingo', 'Mercado', NULL, '2004-04-21', 'Female', 2, '8', 'Lavender St', 'Atok Trail', 'Baguio', 'Benguet', 'Philippines', '2600'),
(35, '20240029', 1, 'Jackson', 'Bautista', 'Pascual', NULL, '2005-07-26', 'Male', 1, '12', 'Peony St', 'Upper Session', 'Baguio', 'Benguet', 'Philippines', '2600'),
(36, '20240030', 2, 'Grace', 'Tan', 'Dizon', NULL, '2002-11-13', 'Female', 4, '15', 'Orchid St', 'Sta. Mesa', 'Manila', 'NCR', 'Philippines', '1016'),
(37, '20240031', 1, 'Samuel', 'Mendoza', 'Lopez', NULL, '2003-06-16', 'Male', 3, '29C', 'Lilac St', 'Slaughter', 'Baguio', 'Benguet', 'Philippines', '2600'),
(38, '20240032', 1, 'Scarlett', 'Rizal', 'Ocampo', NULL, '2004-10-29', 'Female', 2, '35', 'Daisy St', 'Camp 7', 'Baguio', 'Benguet', 'Philippines', '2600'),
(39, '20240033', 2, 'Gabriel', 'Lim', 'Abad', NULL, '2005-02-09', 'Male', 1, '68', 'Crocus St', 'Sampaloc', 'Manila', 'NCR', 'Philippines', '1008'),
(40, '20240034', 1, 'Victoria', 'Castro', 'Gomez', NULL, '2003-09-06', 'Female', 3, '7A', 'Hyacinth St', 'Irisan', 'Baguio', 'Benguet', 'Philippines', '2600'),
(41, '20240035', 1, 'Caleb', 'Garcia', 'Tolentino', NULL, '2004-12-19', 'Male', 2, '14D', 'Poppy St', 'T. Alonzo', 'Baguio', 'Benguet', 'Philippines', '2600'),
(42, '20240036', 2, 'Madeline', 'Perez', 'Velasco', NULL, '2002-04-24', 'Female', 4, '52', 'Amaryllis St', 'Tondo', 'Manila', 'NCR', 'Philippines', '1012'),
(43, '20240037', 1, 'Theodore', 'Alonzo', 'Ramirez', NULL, '2005-05-03', 'Male', 1, '83', 'Anthurium St', 'Lower QM', 'Baguio', 'Benguet', 'Philippines', '2600'),
(44, '20240038', 1, 'Hazel', 'Soriano', 'Aquino', NULL, '2003-01-20', 'Female', 3, '19', 'Begonia St', 'Pacdal', 'Baguio', 'Benguet', 'Philippines', '2600'),
(45, '20240039', 2, 'Ezra', 'Santiago', 'Chua', NULL, '2004-08-30', 'Male', 2, '27', 'Camellia St', NULL, 'Pateros', 'NCR', 'Philippines', '1620'),
(46, '20240040', 1, 'Penelope', 'Uy', 'Rivera', NULL, '2005-11-23', 'Female', 1, '42', 'Freesia St', 'Aurora Hill', 'Baguio', 'Benguet', 'Philippines', '2600'),
(47, '20240041', 1, 'Julian', 'Pascual', 'Domingo', NULL, '2002-03-17', 'Male', 4, '66', 'Gardenia St', 'Loakan', 'Baguio', 'Benguet', 'Philippines', '2600'),
(48, '20240042', 2, 'Ruby', 'Dizon', 'Montoya', NULL, '2004-06-01', 'Female', 2, '13B', 'Jasmine St', NULL, 'Mandaluyong', 'NCR', 'Philippines', '1550'),
(49, '20240043', 1, 'Asher', 'Sy', 'Samson', 'Jr.', '2005-08-14', 'Male', 1, '24', 'Laurel St', 'Legarda', 'Baguio', 'Benguet', 'Philippines', '2600'),
(50, '20240044', 1, 'Stella', 'Marquez', 'Flores', NULL, '2003-04-26', 'Female', 3, '30', 'Magnolia St', 'Bakakeng', 'Baguio', 'Benguet', 'Philippines', '2600'),
(51, '20240045', 2, 'Leo', 'Gonzales', 'Medina', NULL, '2002-10-05', 'Male', 4, '48', 'Orchid St', NULL, 'Pasig', 'NCR', 'Philippines', '1600'),
(52, '20240046', 1, 'Violet', 'Tan', 'Ramos', NULL, '2004-01-31', 'Female', 2, '59', 'Poinsettia St', 'Navy Base', 'Baguio', 'Benguet', 'Philippines', '2600'),
(53, '20240047', 1, 'Wyatt', 'Lim', 'Vargas', NULL, '2005-03-22', 'Male', 1, '76', 'Quince St', 'Cabinet Hill', 'Baguio', 'Benguet', 'Philippines', '2600'),
(54, '20240048', 2, 'Skylar', 'Abad', 'Diaz', NULL, '2003-07-11', 'Female', 3, '91', 'Rain Tree St', NULL, 'Taguig', 'NCR', 'Philippines', '1630'),
(55, '20240049', 1, 'Vincent', 'Corpuz', 'Zamora', NULL, '2004-09-08', 'Male', 2, '105', 'Sage St', 'Kias', 'Baguio', 'Benguet', 'Philippines', '2600'),
(56, '20240050', 1, 'Willow', 'Aquino', 'Cruz', NULL, '2005-02-28', 'Female', 1, '115', 'Thyme St', 'Trancoville', 'Baguio', 'Benguet', 'Philippines', '2600'),
(57, '20202553', 1, 'a1', 'a1', 'a1', '', '2007-12-10', 'Male', 1, '126', 'New Lucban Extension', '', 'Baguio', 'Benguet', 'Philippines', '2100');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_schedule`
--

CREATE TABLE `student_subject_schedule` (
  `StudentScheduleID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `DayOfWeek` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subject_schedule`
--

INSERT INTO `student_subject_schedule` (`StudentScheduleID`, `StudentID`, `SubjectID`, `RoomID`, `DayOfWeek`, `StartTime`, `EndTime`) VALUES
(11, 3, 3, 1, 'Sunday', '19:00:00', '20:00:00'),
(15, 5, 6, 5, 'Wednesday', '10:00:00', '11:00:00'),
(16, 6, 7, 6, 'Wednesday', '11:00:00', '02:00:00'),
(17, 3, 5, 2, 'Monday', '08:00:00', '09:30:00'),
(18, 3, 6, 3, 'Tuesday', '10:00:00', '11:30:00'),
(19, 3, 7, 4, 'Wednesday', '13:00:00', '14:30:00'),
(20, 3, 8, 5, 'Thursday', '15:00:00', '16:30:00'),
(21, 3, 9, 6, 'Friday', '08:00:00', '09:30:00'),
(22, 3, 10, 7, 'Monday', '10:00:00', '11:30:00'),
(23, 4, 11, 8, 'Tuesday', '13:00:00', '14:30:00'),
(24, 4, 12, 9, 'Wednesday', '15:00:00', '16:30:00'),
(25, 4, 3, 10, 'Thursday', '08:00:00', '09:30:00'),
(26, 4, 5, 1, 'Friday', '10:00:00', '11:30:00'),
(27, 4, 6, 2, 'Monday', '13:00:00', '14:30:00'),
(28, 4, 7, 3, 'Tuesday', '15:00:00', '16:30:00'),
(29, 5, 8, 4, 'Wednesday', '08:00:00', '09:30:00'),
(30, 5, 9, 5, 'Thursday', '10:00:00', '11:30:00'),
(31, 5, 10, 6, 'Friday', '13:00:00', '14:30:00'),
(32, 5, 11, 7, 'Monday', '15:00:00', '16:30:00'),
(33, 5, 12, 8, 'Tuesday', '08:00:00', '09:30:00'),
(34, 5, 3, 9, 'Wednesday', '10:00:00', '11:30:00'),
(35, 6, 5, 10, 'Thursday', '13:00:00', '14:30:00'),
(36, 6, 6, 1, 'Friday', '15:00:00', '16:30:00'),
(37, 6, 7, 2, 'Monday', '08:00:00', '09:30:00'),
(38, 6, 8, 3, 'Tuesday', '10:00:00', '11:30:00'),
(39, 6, 9, 4, 'Wednesday', '13:00:00', '14:30:00'),
(40, 6, 10, 5, 'Thursday', '15:00:00', '16:30:00'),
(41, 7, 11, 6, 'Friday', '08:00:00', '09:30:00'),
(42, 7, 12, 7, 'Monday', '10:00:00', '11:30:00'),
(43, 7, 3, 8, 'Tuesday', '13:00:00', '14:30:00'),
(44, 7, 5, 9, 'Wednesday', '15:00:00', '16:30:00'),
(45, 7, 6, 10, 'Thursday', '08:00:00', '09:30:00'),
(46, 7, 7, 1, 'Friday', '10:00:00', '11:30:00'),
(47, 8, 8, 2, 'Monday', '13:00:00', '14:30:00'),
(48, 8, 9, 3, 'Tuesday', '15:00:00', '16:30:00'),
(49, 8, 10, 4, 'Wednesday', '08:00:00', '09:30:00'),
(50, 8, 11, 5, 'Thursday', '10:00:00', '11:30:00'),
(51, 8, 12, 6, 'Friday', '13:00:00', '14:30:00'),
(52, 8, 3, 7, 'Monday', '15:00:00', '16:30:00'),
(53, 9, 5, 8, 'Tuesday', '08:00:00', '09:30:00'),
(54, 9, 6, 9, 'Wednesday', '10:00:00', '11:30:00'),
(55, 9, 7, 10, 'Thursday', '13:00:00', '14:30:00'),
(56, 9, 8, 1, 'Friday', '15:00:00', '16:30:00'),
(57, 9, 9, 2, 'Monday', '08:00:00', '09:30:00'),
(58, 9, 10, 3, 'Tuesday', '10:00:00', '11:30:00'),
(59, 10, 11, 4, 'Wednesday', '13:00:00', '14:30:00'),
(60, 10, 12, 5, 'Thursday', '15:00:00', '16:30:00'),
(61, 10, 3, 6, 'Friday', '08:00:00', '09:30:00'),
(62, 10, 5, 7, 'Monday', '10:00:00', '11:30:00'),
(63, 10, 6, 8, 'Tuesday', '13:00:00', '14:30:00'),
(64, 10, 7, 9, 'Wednesday', '15:00:00', '16:30:00'),
(65, 11, 8, 10, 'Thursday', '08:00:00', '09:30:00'),
(66, 11, 9, 1, 'Friday', '10:00:00', '11:30:00'),
(67, 11, 10, 2, 'Monday', '13:00:00', '14:30:00'),
(68, 11, 11, 3, 'Tuesday', '15:00:00', '16:30:00'),
(69, 11, 12, 4, 'Wednesday', '08:00:00', '09:30:00'),
(70, 11, 3, 5, 'Thursday', '10:00:00', '11:30:00'),
(71, 12, 5, 6, 'Friday', '13:00:00', '14:30:00'),
(72, 12, 6, 7, 'Monday', '15:00:00', '16:30:00'),
(73, 12, 7, 8, 'Tuesday', '08:00:00', '09:30:00'),
(74, 12, 8, 9, 'Wednesday', '10:00:00', '11:30:00'),
(75, 12, 9, 10, 'Thursday', '13:00:00', '14:30:00'),
(76, 12, 10, 1, 'Friday', '15:00:00', '16:30:00'),
(77, 13, 11, 2, 'Monday', '08:00:00', '09:30:00'),
(78, 13, 12, 3, 'Tuesday', '10:00:00', '11:30:00'),
(79, 13, 3, 4, 'Wednesday', '13:00:00', '14:30:00'),
(80, 13, 5, 5, 'Thursday', '15:00:00', '16:30:00'),
(81, 13, 6, 6, 'Friday', '08:00:00', '09:30:00'),
(82, 13, 7, 7, 'Monday', '10:00:00', '11:30:00'),
(83, 14, 8, 8, 'Tuesday', '13:00:00', '14:30:00'),
(84, 14, 9, 9, 'Wednesday', '15:00:00', '16:30:00'),
(85, 14, 10, 10, 'Thursday', '08:00:00', '09:30:00'),
(86, 14, 11, 1, 'Friday', '10:00:00', '11:30:00'),
(87, 14, 12, 2, 'Monday', '13:00:00', '14:30:00'),
(88, 14, 3, 3, 'Tuesday', '15:00:00', '16:30:00'),
(89, 15, 5, 4, 'Wednesday', '08:00:00', '09:30:00'),
(90, 15, 6, 5, 'Thursday', '10:00:00', '11:30:00'),
(91, 15, 7, 6, 'Friday', '13:00:00', '14:30:00'),
(92, 15, 8, 7, 'Monday', '15:00:00', '16:30:00'),
(93, 15, 9, 8, 'Tuesday', '08:00:00', '09:30:00'),
(94, 15, 10, 9, 'Wednesday', '10:00:00', '11:30:00'),
(95, 16, 11, 10, 'Thursday', '13:00:00', '14:30:00'),
(96, 16, 12, 1, 'Friday', '15:00:00', '16:30:00'),
(97, 16, 3, 2, 'Monday', '08:00:00', '09:30:00'),
(98, 16, 5, 3, 'Tuesday', '10:00:00', '11:30:00'),
(99, 16, 6, 4, 'Wednesday', '13:00:00', '14:30:00'),
(100, 16, 7, 5, 'Thursday', '15:00:00', '16:30:00'),
(101, 17, 8, 6, 'Friday', '08:00:00', '09:30:00'),
(102, 17, 9, 7, 'Monday', '10:00:00', '11:30:00'),
(103, 17, 10, 8, 'Tuesday', '13:00:00', '14:30:00'),
(104, 17, 11, 9, 'Wednesday', '15:00:00', '16:30:00'),
(105, 17, 12, 10, 'Thursday', '08:00:00', '09:30:00'),
(106, 17, 3, 1, 'Friday', '10:00:00', '11:30:00'),
(107, 18, 5, 2, 'Monday', '13:00:00', '14:30:00'),
(108, 18, 6, 3, 'Tuesday', '15:00:00', '16:30:00'),
(109, 18, 7, 4, 'Wednesday', '08:00:00', '09:30:00'),
(110, 18, 8, 5, 'Thursday', '10:00:00', '11:30:00'),
(111, 18, 9, 6, 'Friday', '13:00:00', '14:30:00'),
(112, 18, 10, 7, 'Monday', '15:00:00', '16:30:00'),
(113, 19, 11, 8, 'Tuesday', '08:00:00', '09:30:00'),
(114, 19, 12, 9, 'Wednesday', '10:00:00', '11:30:00'),
(115, 19, 3, 10, 'Thursday', '13:00:00', '14:30:00'),
(116, 19, 5, 1, 'Friday', '15:00:00', '16:30:00'),
(117, 19, 6, 2, 'Monday', '08:00:00', '09:30:00'),
(118, 19, 7, 3, 'Tuesday', '10:00:00', '11:30:00'),
(119, 20, 8, 4, 'Wednesday', '13:00:00', '14:30:00'),
(120, 20, 9, 5, 'Thursday', '15:00:00', '16:30:00'),
(121, 20, 10, 6, 'Friday', '08:00:00', '09:30:00'),
(122, 20, 11, 7, 'Monday', '10:00:00', '11:30:00'),
(123, 20, 12, 8, 'Tuesday', '13:00:00', '14:30:00'),
(124, 20, 3, 9, 'Wednesday', '15:00:00', '16:30:00'),
(125, 21, 5, 10, 'Thursday', '08:00:00', '09:30:00'),
(126, 21, 6, 1, 'Friday', '10:00:00', '11:30:00'),
(127, 21, 7, 2, 'Monday', '13:00:00', '14:30:00'),
(128, 21, 8, 3, 'Tuesday', '15:00:00', '16:30:00'),
(129, 21, 9, 4, 'Wednesday', '08:00:00', '09:30:00'),
(130, 21, 10, 5, 'Thursday', '10:00:00', '11:30:00'),
(131, 22, 11, 6, 'Friday', '13:00:00', '14:30:00'),
(132, 22, 12, 7, 'Monday', '15:00:00', '16:30:00'),
(133, 22, 3, 8, 'Tuesday', '08:00:00', '09:30:00'),
(134, 22, 5, 9, 'Wednesday', '10:00:00', '11:30:00'),
(135, 22, 6, 10, 'Thursday', '13:00:00', '14:30:00'),
(136, 22, 7, 1, 'Friday', '15:00:00', '16:30:00'),
(137, 23, 8, 2, 'Monday', '08:00:00', '09:30:00'),
(138, 23, 9, 3, 'Tuesday', '10:00:00', '11:30:00'),
(139, 23, 10, 4, 'Wednesday', '13:00:00', '14:30:00'),
(140, 23, 11, 5, 'Thursday', '15:00:00', '16:30:00'),
(141, 23, 12, 6, 'Friday', '08:00:00', '09:30:00'),
(142, 23, 3, 7, 'Monday', '10:00:00', '11:30:00'),
(143, 24, 5, 8, 'Tuesday', '13:00:00', '14:30:00'),
(144, 24, 6, 9, 'Wednesday', '15:00:00', '16:30:00'),
(145, 24, 7, 10, 'Thursday', '08:00:00', '09:30:00'),
(146, 24, 8, 1, 'Friday', '10:00:00', '11:30:00'),
(147, 24, 9, 2, 'Monday', '13:00:00', '14:30:00'),
(148, 24, 10, 3, 'Tuesday', '15:00:00', '16:30:00'),
(149, 25, 11, 4, 'Wednesday', '08:00:00', '09:30:00'),
(150, 25, 12, 5, 'Thursday', '10:00:00', '11:30:00'),
(151, 25, 3, 6, 'Friday', '13:00:00', '14:30:00'),
(152, 25, 5, 7, 'Monday', '15:00:00', '16:30:00'),
(153, 25, 6, 8, 'Tuesday', '08:00:00', '09:30:00'),
(154, 25, 7, 9, 'Wednesday', '10:00:00', '11:30:00'),
(155, 26, 8, 10, 'Thursday', '13:00:00', '14:30:00'),
(156, 26, 9, 1, 'Friday', '15:00:00', '16:30:00'),
(157, 26, 10, 2, 'Monday', '08:00:00', '09:30:00'),
(158, 26, 11, 3, 'Tuesday', '10:00:00', '11:30:00'),
(159, 26, 12, 4, 'Wednesday', '13:00:00', '14:30:00'),
(160, 26, 3, 5, 'Thursday', '15:00:00', '16:30:00'),
(161, 27, 5, 6, 'Friday', '08:00:00', '09:30:00'),
(162, 27, 6, 7, 'Monday', '10:00:00', '11:30:00'),
(163, 27, 7, 8, 'Tuesday', '13:00:00', '14:30:00'),
(164, 27, 8, 9, 'Wednesday', '15:00:00', '16:30:00'),
(165, 27, 9, 10, 'Thursday', '08:00:00', '09:30:00'),
(166, 27, 10, 1, 'Friday', '10:00:00', '11:30:00'),
(167, 28, 11, 2, 'Monday', '13:00:00', '14:30:00'),
(168, 28, 12, 3, 'Tuesday', '15:00:00', '16:30:00'),
(169, 28, 3, 4, 'Wednesday', '08:00:00', '09:30:00'),
(170, 28, 5, 5, 'Thursday', '10:00:00', '11:30:00'),
(171, 28, 6, 6, 'Friday', '13:00:00', '14:30:00'),
(172, 28, 7, 7, 'Monday', '15:00:00', '16:30:00'),
(173, 29, 8, 8, 'Tuesday', '08:00:00', '09:30:00'),
(174, 29, 9, 9, 'Wednesday', '10:00:00', '11:30:00'),
(175, 29, 10, 10, 'Thursday', '13:00:00', '14:30:00'),
(176, 29, 11, 1, 'Friday', '15:00:00', '16:30:00'),
(177, 29, 12, 2, 'Monday', '08:00:00', '09:30:00'),
(178, 29, 3, 3, 'Tuesday', '10:00:00', '11:30:00'),
(179, 30, 5, 4, 'Wednesday', '13:00:00', '14:30:00'),
(180, 30, 6, 5, 'Thursday', '15:00:00', '16:30:00'),
(181, 30, 7, 6, 'Friday', '08:00:00', '09:30:00'),
(182, 30, 8, 7, 'Monday', '10:00:00', '11:30:00'),
(183, 30, 9, 8, 'Tuesday', '13:00:00', '14:30:00'),
(184, 30, 10, 9, 'Wednesday', '15:00:00', '16:30:00'),
(185, 31, 11, 10, 'Thursday', '08:00:00', '09:30:00'),
(186, 31, 12, 1, 'Friday', '10:00:00', '11:30:00'),
(187, 31, 3, 2, 'Monday', '13:00:00', '14:30:00'),
(188, 31, 5, 3, 'Tuesday', '15:00:00', '16:30:00'),
(189, 31, 6, 4, 'Wednesday', '08:00:00', '09:30:00'),
(190, 31, 7, 5, 'Thursday', '10:00:00', '11:30:00'),
(191, 32, 8, 6, 'Friday', '13:00:00', '14:30:00'),
(192, 32, 9, 7, 'Monday', '15:00:00', '16:30:00'),
(193, 32, 10, 8, 'Tuesday', '08:00:00', '09:30:00'),
(194, 32, 11, 9, 'Wednesday', '10:00:00', '11:30:00'),
(195, 32, 12, 10, 'Thursday', '13:00:00', '14:30:00'),
(196, 32, 3, 1, 'Friday', '15:00:00', '16:30:00'),
(197, 33, 5, 2, 'Monday', '08:00:00', '09:30:00'),
(198, 33, 6, 3, 'Tuesday', '10:00:00', '11:30:00'),
(199, 33, 7, 4, 'Wednesday', '13:00:00', '14:30:00'),
(200, 33, 8, 5, 'Thursday', '15:00:00', '16:30:00'),
(201, 33, 9, 6, 'Friday', '08:00:00', '09:30:00'),
(202, 33, 10, 7, 'Monday', '10:00:00', '11:30:00'),
(203, 34, 11, 8, 'Tuesday', '13:00:00', '14:30:00'),
(204, 34, 12, 9, 'Wednesday', '15:00:00', '16:30:00'),
(205, 34, 3, 10, 'Thursday', '08:00:00', '09:30:00'),
(206, 34, 5, 1, 'Friday', '10:00:00', '11:30:00'),
(207, 34, 6, 2, 'Monday', '13:00:00', '14:30:00'),
(208, 34, 7, 3, 'Tuesday', '15:00:00', '16:30:00'),
(209, 35, 8, 4, 'Wednesday', '08:00:00', '09:30:00'),
(210, 35, 9, 5, 'Thursday', '10:00:00', '11:30:00'),
(211, 35, 10, 6, 'Friday', '13:00:00', '14:30:00'),
(212, 35, 11, 7, 'Monday', '15:00:00', '16:30:00'),
(213, 35, 12, 8, 'Tuesday', '08:00:00', '09:30:00'),
(214, 35, 3, 9, 'Wednesday', '10:00:00', '11:30:00'),
(215, 36, 5, 10, 'Thursday', '13:00:00', '14:30:00'),
(216, 36, 6, 1, 'Friday', '15:00:00', '16:30:00'),
(217, 36, 7, 2, 'Monday', '08:00:00', '09:30:00'),
(218, 36, 8, 3, 'Tuesday', '10:00:00', '11:30:00'),
(219, 36, 9, 4, 'Wednesday', '13:00:00', '14:30:00'),
(220, 36, 10, 5, 'Thursday', '15:00:00', '16:30:00'),
(221, 37, 11, 6, 'Friday', '08:00:00', '09:30:00'),
(222, 37, 12, 7, 'Monday', '10:00:00', '11:30:00'),
(223, 37, 3, 8, 'Tuesday', '13:00:00', '14:30:00'),
(224, 37, 5, 9, 'Wednesday', '15:00:00', '16:30:00'),
(225, 37, 6, 10, 'Thursday', '08:00:00', '09:30:00'),
(226, 37, 7, 1, 'Friday', '10:00:00', '11:30:00'),
(227, 38, 8, 2, 'Monday', '13:00:00', '14:30:00'),
(228, 38, 9, 3, 'Tuesday', '15:00:00', '16:30:00'),
(229, 38, 10, 4, 'Wednesday', '08:00:00', '09:30:00'),
(230, 38, 11, 5, 'Thursday', '10:00:00', '11:30:00'),
(231, 38, 12, 6, 'Friday', '13:00:00', '14:30:00'),
(232, 38, 3, 7, 'Monday', '15:00:00', '16:30:00'),
(233, 39, 5, 8, 'Tuesday', '08:00:00', '09:30:00'),
(234, 39, 6, 9, 'Wednesday', '10:00:00', '11:30:00'),
(235, 39, 7, 10, 'Thursday', '13:00:00', '14:30:00'),
(236, 39, 8, 1, 'Friday', '15:00:00', '16:30:00'),
(238, 39, 10, 3, 'Tuesday', '10:00:00', '11:30:00'),
(239, 40, 11, 4, 'Wednesday', '13:00:00', '14:30:00'),
(240, 40, 12, 5, 'Thursday', '15:00:00', '16:30:00'),
(241, 40, 3, 6, 'Friday', '08:00:00', '09:30:00'),
(242, 40, 5, 7, 'Monday', '10:00:00', '11:30:00'),
(243, 40, 6, 8, 'Tuesday', '13:00:00', '14:30:00'),
(244, 40, 7, 9, 'Wednesday', '15:00:00', '16:30:00'),
(245, 41, 8, 10, 'Thursday', '08:00:00', '09:30:00'),
(246, 41, 9, 1, 'Friday', '10:00:00', '11:30:00'),
(247, 41, 10, 2, 'Monday', '13:00:00', '14:30:00'),
(248, 41, 11, 3, 'Tuesday', '15:00:00', '16:30:00'),
(249, 41, 12, 4, 'Wednesday', '08:00:00', '09:30:00'),
(250, 41, 3, 5, 'Thursday', '10:00:00', '11:30:00'),
(251, 42, 5, 6, 'Friday', '13:00:00', '14:30:00'),
(252, 42, 6, 7, 'Monday', '15:00:00', '16:30:00'),
(253, 42, 7, 8, 'Tuesday', '08:00:00', '09:30:00'),
(254, 42, 8, 9, 'Wednesday', '10:00:00', '11:30:00'),
(255, 42, 9, 10, 'Thursday', '13:00:00', '14:30:00'),
(256, 42, 10, 1, 'Friday', '15:00:00', '16:30:00'),
(257, 43, 11, 2, 'Monday', '08:00:00', '09:30:00'),
(258, 43, 12, 3, 'Tuesday', '10:00:00', '11:30:00'),
(259, 43, 3, 4, 'Wednesday', '13:00:00', '14:30:00'),
(260, 43, 5, 5, 'Thursday', '15:00:00', '16:30:00'),
(261, 43, 6, 6, 'Friday', '08:00:00', '09:30:00'),
(262, 43, 7, 7, 'Monday', '10:00:00', '11:30:00'),
(263, 44, 8, 8, 'Tuesday', '13:00:00', '14:30:00'),
(264, 44, 9, 9, 'Wednesday', '15:00:00', '16:30:00'),
(265, 44, 10, 10, 'Thursday', '08:00:00', '09:30:00'),
(266, 44, 11, 1, 'Friday', '10:00:00', '11:30:00'),
(267, 44, 12, 2, 'Monday', '13:00:00', '14:30:00'),
(268, 44, 3, 3, 'Tuesday', '15:00:00', '16:30:00'),
(269, 45, 5, 4, 'Wednesday', '08:00:00', '09:30:00'),
(270, 45, 6, 5, 'Thursday', '10:00:00', '11:30:00'),
(271, 45, 7, 6, 'Friday', '13:00:00', '14:30:00'),
(272, 45, 8, 7, 'Monday', '15:00:00', '16:30:00'),
(273, 45, 9, 8, 'Tuesday', '08:00:00', '09:30:00'),
(274, 45, 10, 9, 'Wednesday', '10:00:00', '11:30:00'),
(275, 46, 11, 10, 'Thursday', '13:00:00', '14:30:00'),
(276, 46, 12, 1, 'Friday', '15:00:00', '16:30:00'),
(277, 46, 3, 2, 'Monday', '08:00:00', '09:30:00'),
(278, 46, 5, 3, 'Tuesday', '10:00:00', '11:30:00'),
(279, 46, 6, 4, 'Wednesday', '13:00:00', '14:30:00'),
(280, 46, 7, 5, 'Thursday', '15:00:00', '16:30:00'),
(281, 47, 8, 6, 'Friday', '08:00:00', '09:30:00'),
(282, 47, 9, 7, 'Monday', '10:00:00', '11:30:00'),
(283, 47, 10, 8, 'Tuesday', '13:00:00', '14:30:00'),
(284, 47, 11, 9, 'Wednesday', '15:00:00', '16:30:00'),
(285, 47, 12, 10, 'Thursday', '08:00:00', '09:30:00'),
(286, 47, 3, 1, 'Friday', '10:00:00', '11:30:00'),
(287, 48, 5, 2, 'Monday', '13:00:00', '14:30:00'),
(288, 48, 6, 3, 'Tuesday', '15:00:00', '16:30:00'),
(289, 48, 7, 4, 'Wednesday', '08:00:00', '09:30:00'),
(290, 48, 8, 5, 'Thursday', '10:00:00', '11:30:00'),
(291, 48, 9, 6, 'Friday', '13:00:00', '14:30:00'),
(292, 48, 10, 7, 'Monday', '15:00:00', '16:30:00'),
(293, 49, 11, 8, 'Tuesday', '08:00:00', '09:30:00'),
(294, 49, 12, 9, 'Wednesday', '10:00:00', '11:30:00'),
(295, 49, 3, 10, 'Thursday', '13:00:00', '14:30:00'),
(296, 49, 5, 1, 'Friday', '15:00:00', '16:30:00'),
(297, 49, 6, 2, 'Monday', '08:00:00', '09:30:00'),
(298, 49, 7, 3, 'Tuesday', '10:00:00', '11:30:00'),
(299, 50, 8, 4, 'Wednesday', '13:00:00', '14:30:00'),
(300, 50, 9, 5, 'Thursday', '15:00:00', '16:30:00'),
(301, 50, 10, 6, 'Friday', '08:00:00', '09:30:00'),
(302, 50, 11, 7, 'Monday', '10:00:00', '11:30:00'),
(303, 50, 12, 8, 'Tuesday', '13:00:00', '14:30:00'),
(304, 50, 3, 9, 'Wednesday', '15:00:00', '16:30:00'),
(305, 51, 5, 10, 'Thursday', '08:00:00', '09:30:00'),
(306, 51, 6, 1, 'Friday', '10:00:00', '11:30:00'),
(307, 51, 7, 2, 'Monday', '13:00:00', '14:30:00'),
(308, 51, 8, 3, 'Tuesday', '15:00:00', '16:30:00'),
(309, 51, 9, 4, 'Wednesday', '08:00:00', '09:30:00'),
(310, 51, 10, 5, 'Thursday', '10:00:00', '11:30:00'),
(311, 52, 11, 6, 'Friday', '13:00:00', '14:30:00'),
(312, 52, 12, 7, 'Monday', '15:00:00', '16:30:00'),
(313, 52, 3, 8, 'Tuesday', '08:00:00', '09:30:00'),
(314, 52, 5, 9, 'Wednesday', '10:00:00', '11:30:00'),
(315, 52, 6, 10, 'Thursday', '13:00:00', '14:30:00'),
(316, 52, 7, 1, 'Friday', '15:00:00', '16:30:00'),
(317, 53, 8, 2, 'Monday', '08:00:00', '09:30:00'),
(318, 53, 9, 3, 'Tuesday', '10:00:00', '11:30:00'),
(319, 53, 10, 4, 'Wednesday', '13:00:00', '14:30:00'),
(320, 53, 11, 5, 'Thursday', '15:00:00', '16:30:00'),
(321, 53, 12, 6, 'Friday', '08:00:00', '09:30:00'),
(322, 53, 3, 7, 'Monday', '10:00:00', '11:30:00'),
(323, 54, 5, 8, 'Tuesday', '13:00:00', '14:30:00'),
(324, 54, 6, 9, 'Wednesday', '15:00:00', '16:30:00'),
(325, 54, 7, 10, 'Thursday', '08:00:00', '09:30:00'),
(326, 54, 8, 1, 'Friday', '10:00:00', '11:30:00'),
(327, 54, 9, 2, 'Monday', '13:00:00', '14:30:00'),
(328, 54, 10, 3, 'Tuesday', '15:00:00', '16:30:00'),
(329, 55, 11, 4, 'Wednesday', '08:00:00', '09:30:00'),
(330, 55, 12, 5, 'Thursday', '10:00:00', '11:30:00'),
(331, 55, 3, 6, 'Friday', '13:00:00', '14:30:00'),
(332, 55, 5, 7, 'Monday', '15:00:00', '16:30:00'),
(333, 55, 6, 8, 'Tuesday', '08:00:00', '09:30:00'),
(334, 55, 7, 9, 'Wednesday', '10:00:00', '11:30:00'),
(335, 56, 8, 10, 'Thursday', '13:00:00', '14:30:00'),
(336, 56, 9, 1, 'Friday', '15:00:00', '16:30:00'),
(337, 56, 10, 2, 'Monday', '08:00:00', '09:30:00'),
(338, 56, 11, 3, 'Tuesday', '10:00:00', '11:30:00'),
(339, 56, 12, 4, 'Wednesday', '13:00:00', '14:30:00'),
(340, 56, 3, 5, 'Thursday', '15:00:00', '16:30:00'),
(342, 3, 8, 5, 'Wednesday', '20:00:00', '21:00:00'),
(343, 57, 13, 5, 'Wednesday', '20:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SubjectID`, `SubjectName`) VALUES
(3, 'INASEC'),
(5, 'SYSADM'),
(6, 'NETANA'),
(7, 'IMDBSE2'),
(8, 'CERTIF'),
(9, 'WEBSYS'),
(10, 'SYSINT'),
(11, 'TECHNO'),
(12, 'METHOD'),
(13, 'MATH');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `StudentScheduleID` (`StudentScheduleID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `StudentNumber` (`StudentNumber`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `student_subject_schedule`
--
ALTER TABLE `student_subject_schedule`
  ADD PRIMARY KEY (`StudentScheduleID`),
  ADD UNIQUE KEY `uk_student_day_subject` (`StudentID`,`DayOfWeek`,`SubjectID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `RoomID` (`RoomID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`SubjectID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `student_subject_schedule`
--
ALTER TABLE `student_subject_schedule`
  MODIFY `StudentScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_schedule` FOREIGN KEY (`StudentScheduleID`) REFERENCES `student_subject_schedule` (`StudentScheduleID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_student` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_subject` FOREIGN KEY (`SubjectID`) REFERENCES `subject` (`SubjectID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student_subject_schedule`
--
ALTER TABLE `student_subject_schedule`
  ADD CONSTRAINT `student_subject_schedule_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subject_schedule_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subject` (`SubjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subject_schedule_ibfk_3` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
