-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2024 at 10:10 AM
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
-- Database: `college_admission`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAdmissionStatus` (IN `p_studentID` INT, IN `p_courseID` INT, IN `p_teacherID` INT, IN `p_admissionStatus` VARCHAR(40))   BEGIN
    UPDATE admissions 
    SET ApprovedByTeacherID = p_teacherID, 
        AdmissionStatus = p_admissionStatus
    WHERE StudentID = p_studentID AND CourseID = p_courseID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `AdmissionID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `ApprovedByTeacherID` int(11) DEFAULT NULL,
  `AdmissionStatus` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`AdmissionID`, `StudentID`, `CourseID`, `ApprovedByTeacherID`, `AdmissionStatus`) VALUES
(1, 2, 101, 1, 'Approved'),
(4, 2, 102, 1, 'Approved');

--
-- Triggers `admissions`
--
DELIMITER $$
CREATE TRIGGER `after_admission_approved` AFTER UPDATE ON `admissions` FOR EACH ROW BEGIN
    DECLARE student_first_name VARCHAR(255);
    DECLARE student_last_name VARCHAR(255);
    DECLARE course_name VARCHAR(255);

    IF OLD.AdmissionStatus <> NEW.AdmissionStatus AND NEW.AdmissionStatus = 'Approved' THEN
        -- Retrieve student's first name and last name
        SELECT FirstName, LastName INTO student_first_name, student_last_name
        FROM students
        WHERE StudentID = NEW.StudentID;

        -- Retrieve course name
        SELECT CourseName INTO course_name
        FROM courses
        WHERE CourseID = NEW.CourseID;

        -- Insert message into admission_messages table
        INSERT INTO admission_messages (student_id, course_name, message)
        VALUES (NEW.StudentID, course_name, CONCAT('Admission for ', student_first_name, ' ', student_last_name, ' in ', course_name, ' has been approved.'));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_admission_rejected` AFTER UPDATE ON `admissions` FOR EACH ROW BEGIN
    DECLARE student_first_name VARCHAR(255);
    DECLARE student_last_name VARCHAR(255);
    DECLARE course_name VARCHAR(255);

    IF OLD.AdmissionStatus <> NEW.AdmissionStatus AND NEW.AdmissionStatus = 'Rejected' THEN
        -- Retrieve student's first name and last name
        SELECT FirstName, LastName INTO student_first_name, student_last_name
        FROM students
        WHERE StudentID = NEW.StudentID;

        -- Retrieve course name
        SELECT CourseName INTO course_name
        FROM courses
        WHERE CourseID = NEW.CourseID;

        -- Insert message into admission_messages table
        INSERT INTO admission_messages (student_id, course_name, message)
        VALUES (NEW.StudentID, course_name, CONCAT('Admission for ', student_first_name, ' ', student_last_name, ' in ', course_name, ' has been rejected.'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admission_messages`
--

CREATE TABLE `admission_messages` (
  `message_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_messages`
--

INSERT INTO `admission_messages` (`message_id`, `student_id`, `course_name`, `message`, `created_at`) VALUES
(3, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-23 14:33:43'),
(4, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-23 14:34:00'),
(5, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-23 16:51:14'),
(6, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-23 16:51:20'),
(7, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-23 18:15:22'),
(8, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-23 18:16:50'),
(9, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-23 18:18:30'),
(10, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-23 18:31:45'),
(11, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-24 11:14:24'),
(12, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-24 11:14:35'),
(13, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-24 11:14:42'),
(14, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-24 11:14:47'),
(15, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-24 11:14:56'),
(16, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-27 06:07:25'),
(17, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-27 06:07:37'),
(18, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-29 11:21:28'),
(19, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-29 11:23:45'),
(20, 2, 'Science', 'Admission for harsh kakodkar in Science has been rejected.', '2024-03-29 11:27:35'),
(21, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been approved.', '2024-03-29 11:27:55'),
(22, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been rejected.', '2024-03-29 11:29:11'),
(23, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been approved.', '2024-03-29 11:33:33'),
(24, 2, 'Science', 'Admission for harsh kakodkar in Science has been approved.', '2024-03-29 11:46:18'),
(25, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been rejected.', '2024-03-29 12:09:56'),
(26, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been approved.', '2024-03-29 12:10:06'),
(27, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been rejected.', '2024-03-29 12:44:11'),
(28, 2, 'Commerce', 'Admission for harsh kakodkar in Commerce has been approved.', '2024-03-29 21:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `CourseID` int(11) NOT NULL,
  `CourseName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`CourseID`, `CourseName`) VALUES
(101, 'Science'),
(102, 'Commerce'),
(103, 'Arts');

-- --------------------------------------------------------

--
-- Table structure for table `educationdetails`
--

CREATE TABLE `educationdetails` (
  `StudentID` int(11) NOT NULL,
  `CourseID` int(11) NOT NULL,
  `Standard10_English` int(11) DEFAULT NULL,
  `Standard10_Hindi` int(11) DEFAULT NULL,
  `Standard10_MarathiORKonkani` int(11) DEFAULT NULL,
  `Standard10_Maths` int(11) DEFAULT NULL,
  `Standard10_Science` int(11) DEFAULT NULL,
  `Standard10_Social_Science` int(11) DEFAULT NULL,
  `MarksheetPhoto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educationdetails`
--

INSERT INTO `educationdetails` (`StudentID`, `CourseID`, `Standard10_English`, `Standard10_Hindi`, `Standard10_MarathiORKonkani`, `Standard10_Maths`, `Standard10_Science`, `Standard10_Social_Science`, `MarksheetPhoto`) VALUES
(2, 101, 2, 2, 45, 38, 2, 2, 'FormValidation.PNG');

--
-- Triggers `educationdetails`
--
DELIMITER $$
CREATE TRIGGER `check_percentage_before_insert` BEFORE INSERT ON `educationdetails` FOR EACH ROW BEGIN
    IF NEW.CourseID = 101 AND 
       ((NEW.Standard10_English + NEW.Standard10_Hindi + NEW.Standard10_MarathiORKonkani + NEW.Standard10_Maths + NEW.Standard10_Science + NEW.Standard10_Social_Science) / 6) < 70 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Percentage must be above or equal to 70 ';
    ELSEIF NEW.CourseID = 102 AND 
       ((NEW.Standard10_English + NEW.Standard10_Hindi + NEW.Standard10_MarathiORKonkani + NEW.Standard10_Maths + NEW.Standard10_Science + NEW.Standard10_Social_Science) / 6) < 50 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Percentage must be above or equal to 50';
    ELSEIF NEW.CourseID = 103 AND 
       ((NEW.Standard10_English + NEW.Standard10_Hindi + NEW.Standard10_MarathiORKonkani + NEW.Standard10_Maths + NEW.Standard10_Science + NEW.Standard10_Social_Science) / 6) < 40 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Percentage must be above or equal to 40 ';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `studentinformation`
-- (See below for the actual view)
--
CREATE TABLE `studentinformation` (
`StudentFirstName` varchar(50)
,`StudentLastName` varchar(50)
,`StudentEmail` varchar(100)
,`StudentPhoneNumber` varchar(15)
,`StudentAddress` varchar(255)
,`StudentPhoto` varchar(255)
,`CourseName` varchar(50)
,`AdmissionStatus` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `FirstName`, `LastName`, `Email`, `Password`, `PhoneNumber`, `Address`, `Photo`) VALUES
(1, 'asdg', 'asdg', 'asg', 'asg', 'asg', 'asg', 'Images/kakashi-sharingan-kurama-uhdpaper.com-4K-51.jpg'),
(2, 'harsh', 'kakodkar', 'harshkakodkar111@gmail.com', 'abc', '9075877527', 'curchorem', 'Images/profile_picture.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `TeacherID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Photo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`TeacherID`, `FirstName`, `LastName`, `Email`, `Password`, `PhoneNumber`, `Address`, `Photo`) VALUES
(1, 'Teacher', 'ji', 'harshkakodkar111@gmail.com', '123456', '123456', 'curchorem', 'Images/profile_picture.jpeg');

-- --------------------------------------------------------

--
-- Structure for view `studentinformation`
--
DROP TABLE IF EXISTS `studentinformation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `studentinformation`  AS SELECT `s`.`FirstName` AS `StudentFirstName`, `s`.`LastName` AS `StudentLastName`, `s`.`Email` AS `StudentEmail`, `s`.`PhoneNumber` AS `StudentPhoneNumber`, `s`.`Address` AS `StudentAddress`, `s`.`Photo` AS `StudentPhoto`, `c`.`CourseName` AS `CourseName`, `a`.`AdmissionStatus` AS `AdmissionStatus` FROM ((`students` `s` join `admissions` `a` on(`s`.`StudentID` = `a`.`StudentID`)) join `courses` `c` on(`a`.`CourseID` = `c`.`CourseID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`AdmissionID`);

--
-- Indexes for table `admission_messages`
--
ALTER TABLE `admission_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`CourseID`);

--
-- Indexes for table `educationdetails`
--
ALTER TABLE `educationdetails`
  ADD PRIMARY KEY (`StudentID`,`CourseID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`TeacherID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `AdmissionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `admission_messages`
--
ALTER TABLE `admission_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `educationdetails`
--
ALTER TABLE `educationdetails`
  ADD CONSTRAINT `educationdetails_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`),
  ADD CONSTRAINT `educationdetails_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `courses` (`CourseID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
