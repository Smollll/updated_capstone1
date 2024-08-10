-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2024 at 01:43 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_creation`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `archive_id` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `instructor_email` varchar(128) NOT NULL,
  `course_Id` int(11) NOT NULL,
  `lessonNumber` varchar(50) NOT NULL,
  `videoPath` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`archive_id`, `videoId`, `instructor_email`, `course_Id`, `lessonNumber`, `videoPath`) VALUES
(49, 118, 'john@gmail.com', 199, 'Lesson 1', '../courses/john@gmail.com/Xian Tuli/Lesson 1/Talong kong Supot Vol. 1.mp4'),
(50, 119, 'john@gmail.com', 199, 'Lesson 1', '../video-archive/john@gmail.com/Xian Tuli\\Lesson 1\\Talong kong Supot Vol. 1.mp4'),
(51, 119, 'john@gmail.com', 199, 'Lesson 1', '../video-archive/john@gmail.com/Xian Tuli\\Lesson 1\\Talong kong Supot Vol. 2.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseId` int(11) NOT NULL,
  `courseName` varchar(128) NOT NULL,
  `instructor_email` varchar(128) NOT NULL,
  `difficulty` varchar(20) NOT NULL,
  `lessons` int(6) NOT NULL,
  `description` text NOT NULL,
  `course_ratings` float NOT NULL,
  `thumbnail` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseId`, `courseName`, `instructor_email`, `difficulty`, `lessons`, `description`, `course_ratings`, `thumbnail`, `created_at`, `updated_at`) VALUES
(200, 'Talong ko malaki', 'john@gmail.com', 'Expert', 1, 'Wala lang', 0, 'Talong ko malaki.jpg', '2024-07-25 17:52:57', '2024-07-25 17:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_course`
--

CREATE TABLE `enrolled_course` (
  `enrolled_course_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `progress` varchar(15) NOT NULL,
  `date_finished` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolled_course`
--

INSERT INTO `enrolled_course` (`enrolled_course_id`, `course_id`, `learner_id`, `progress`, `date_finished`) VALUES
(1, 200, 1, '10', '07/31/2024');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `instructor_name` varchar(80) NOT NULL,
  `email` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `ratings` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`instructor_name`, `email`, `address`, `contact_no`, `ratings`) VALUES
('John', 'john@gmail.com', 'Pinas', '09765407546', 4),
('Juan Dela Cruz', 'juan@gmail.com', 'Philippines', '09765407546', 3.8);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `correct_answer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_choices`
--

CREATE TABLE `question_choices` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choice_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `timer` int(11) NOT NULL,
  `passing_grade` int(11) NOT NULL,
  `course_Id` int(11) NOT NULL,
  `lessonNumber` varchar(20) NOT NULL,
  `instructor_email` varchar(128) NOT NULL,
  `num_questions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `profile_picture` varchar(50) NOT NULL,
  `rating` float NOT NULL,
  `review` text NOT NULL,
  `reviewed_instructor` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewId`, `name`, `profile_picture`, `rating`, `review`, `reviewed_instructor`) VALUES
(6, 'Eugene Van', 'Eugene Van.png', 5, 'Eyyyy', 'john@gmail.com'),
(7, 'agrilearn', 'agrilearn.png', 3, 'Okay lang need some improvements pa', 'john@gmail.com'),
(8, 'agrilearn', 'agrilearn.png', 5, 'ayos ah nag improve na', 'john@gmail.com'),
(9, 'agrilearn', 'agrilearn.png', 1, 'yun lang.', 'john@gmail.com'),
(10, 'agrilearn', 'agrilearn.png', 5, 'Sheeshhh', 'john@gmail.com'),
(11, 'Test1', 'Test1.png', 5, 'Galing!', 'john@gmail.com'),
(12, 'agrilearn', 'agrilearn.png', 2, 'Keep it consistent', 'john@gmail.com'),
(13, 'agrilearn', 'agrilearn.png', 4, 'Nice!', 'john@gmail.com'),
(14, 'agrilearn', 'agrilearn.png', 5, 'Nice improvements! Keep it up!', 'john@gmail.com'),
(15, 'TrashToCash', 'TrashToCash.png', 5, 'Very Good Explanation', 'john@gmail.com'),
(16, 'agrilearn', 'agrilearn.png', 2, 'Inconsistent', 'john@gmail.com'),
(17, 'agrilearn', 'agrilearn.png', 1, 'Bobo', 'john@gmail.com'),
(18, 'agrilearn', 'agrilearn.png', 5, 'Nice!', 'john@gmail.com'),
(19, 'agrilearn2', 'agrilearn2.png', 5, 'Your Contents are getting better', 'john@gmail.com'),
(20, 'agrilearn2', 'agrilearn2.png', 5, 'Keep up the Good Work', 'john@gmail.com'),
(21, 'agrilearn2', 'agrilearn2.png', 3, 'Sakto lang', 'john@gmail.com'),
(22, 'agrilearn2', 'agrilearn2.png', 5, 'Good Job!', 'john@gmail.com'),
(23, 'agrilearn2', 'agrilearn2.png', 5, 'Nice! Goodjob!', 'john@gmail.com'),
(24, 'agrilearn2', 'agrilearn2.png', 5, 'That\'s Awesome!', 'john@gmail.com'),
(25, 'TrashToCash', 'TrashToCash.png', 1, 'Not Good', 'john@gmail.com'),
(26, 'agrilearn', 'agrilearn.png', 5, 'Great Job !', 'john@gmail.com'),
(27, 'agrilearn2', 'agrilearn2.png', 5, 'Consistent na! Galinggg!!!', 'john@gmail.com'),
(28, 'agrilearn2', 'agrilearn2.png', 4, 'More Improvements pa', 'juan@gmail.com'),
(29, 'agrilearn2', 'agrilearn2.png', 5, 'Nice!', 'juan@gmail.com'),
(30, 'agrilearn2', 'agrilearn2.png', 3, 'Inconsistent', 'juan@gmail.com'),
(31, 'Test1', 'Test1.png', 5, 'Nice', 'juan@gmail.com'),
(32, 'agrilearn2', 'agrilearn2.png', 1, 'bobo', 'juan@gmail.com'),
(33, 'agrilearn', 'agrilearn.png', 5, 'nice', 'juan@gmail.com'),
(34, 'agrilearn', 'agrilearn.png', 1, 'bobo ka', 'john@gmail.com'),
(35, 'agrilearn', 'agrilearn.png', 5, 'nice1', 'john@gmail.com'),
(36, 'xian', 'xian.png', 1, 'pangit ', 'john@gmail.com'),
(37, 'xian', 'xian.png', 1, 'pangit paramg gumawa lang', 'john@gmail.com'),
(38, 'xian', 'xian.png', 5, 'good job', 'john@gmail.com'),
(39, 'xian', 'xian.png', 5, 'eyy', 'john@gmail.com'),
(40, 'Xian', 'Xian.jpg', 5, 'Bossing kamusta ang buhay buhay', 'john@gmail.com'),
(41, 'Xian', 'Xian.jpg', 2, 'Dhdhd', 'john@gmail.com'),
(42, 'Vantot', 'Vantot.jpg', 5, 'Pogi ko', 'john@gmail.com'),
(43, 'Fugeo bug analyzer ', 'Fugeo bug analyzer .jpg', 5, 'Bug or bold?', 'john@gmail.com'),
(44, 'Pogi ko ', 'Pogi ko .jpg', 5, 'Umangat kana ', 'john@gmail.com'),
(45, 'agrilearn', 'agrilearn.png', 5, 'Nc1', 'john@gmail.com'),
(46, 'Pogi si xian', 'Pogi si xian.jpg', 5, 'Aangat na \'yan', 'john@gmail.com'),
(47, 'Xian malaki ano', 'Xian malaki ano.jpg', 5, 'Baha na kami dito boss ', 'john@gmail.com'),
(48, 'Xian', 'Xian.jpg', 1, 'Supot', 'john@gmail.com'),
(49, 'Xian', 'Xian.jpg', 1, 'Supot ', 'john@gmail.com'),
(50, 'Xian', 'Xian.jpg', 5, 'Supot', 'john@gmail.com'),
(51, 'Xian', 'Xian.jpg', 5, 'Supot ', 'john@gmail.com'),
(52, 'Xian', 'Xian.jpg', 5, 'Supot ako hehe', 'john@gmail.com'),
(53, 'Xian', 'Xian.jpg', 5, 'Supot talaga ako hehe', 'john@gmail.com'),
(54, 'agrilearn', 'agrilearn.png', 5, 'klnkl', 'john@gmail.com'),
(55, 'Xian', 'Xian.jpg', 5, 'Supot ako hehe', 'john@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `test_learners`
--

CREATE TABLE `test_learners` (
  `learner_id` int(11) NOT NULL,
  `learner_fullname` varchar(100) NOT NULL,
  `learner_email` varchar(128) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_learners`
--

INSERT INTO `test_learners` (`learner_id`, `learner_fullname`, `learner_email`, `phone`, `address`) VALUES
(1, 'Nigga', 'Nigga@gmail.com', '21312323', 'Pinas');

-- --------------------------------------------------------

--
-- Table structure for table `videolessons`
--

CREATE TABLE `videolessons` (
  `videoId` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lessonNumber` varchar(10) NOT NULL,
  `videoPath` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`archive_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseId`),
  ADD KEY `instructor_email` (`instructor_email`);

--
-- Indexes for table `enrolled_course`
--
ALTER TABLE `enrolled_course`
  ADD PRIMARY KEY (`enrolled_course_id`),
  ADD KEY `course_id` (`course_id`,`learner_id`),
  ADD KEY `learner_id` (`learner_id`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `question_choices`
--
ALTER TABLE `question_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseId` (`course_Id`),
  ADD KEY `instructor_email` (`instructor_email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewId`);

--
-- Indexes for table `test_learners`
--
ALTER TABLE `test_learners`
  ADD PRIMARY KEY (`learner_id`);

--
-- Indexes for table `videolessons`
--
ALTER TABLE `videolessons`
  ADD PRIMARY KEY (`videoId`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `enrolled_course`
--
ALTER TABLE `enrolled_course`
  MODIFY `enrolled_course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `question_choices`
--
ALTER TABLE `question_choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `test_learners`
--
ALTER TABLE `test_learners`
  MODIFY `learner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `videolessons`
--
ALTER TABLE `videolessons`
  MODIFY `videoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_email`) REFERENCES `instructor` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrolled_course`
--
ALTER TABLE `enrolled_course`
  ADD CONSTRAINT `enrolled_course_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`courseId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrolled_course_ibfk_2` FOREIGN KEY (`learner_id`) REFERENCES `test_learners` (`learner_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_choices`
--
ALTER TABLE `question_choices`
  ADD CONSTRAINT `question_choices_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`instructor_email`) REFERENCES `instructor` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`course_Id`) REFERENCES `courses` (`courseId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `videolessons`
--
ALTER TABLE `videolessons`
  ADD CONSTRAINT `videolessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`courseId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
