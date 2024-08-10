<?php
session_start();
include 'conn.php';

// Fetch all instructor names
$instructorNames = [];
$sql_instructors = "SELECT email, instructor_name FROM instructor";
$result_instructors = $conn->query($sql_instructors);

if ($result_instructors->num_rows > 0) {
    while ($row = $result_instructors->fetch_assoc()) {
        $instructorNames[$row['email']] = $row['instructor_name'];
    }
} else {
    echo json_encode(['error' => 'No instructors found.']);
    exit;
}

// Fetch all courses from the database
$sql_courses = "SELECT courseId, courseName, instructor_email, difficulty, description, lessons, course_ratings, thumbnail FROM courses";
$result_courses = $conn->query($sql_courses);

$coursesWithThumbnails = [];

if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $courseId = $row['courseId'];
        $courseName = $row['courseName'];
        $instructorEmail = $row['instructor_email'];
        $difficulty = $row['difficulty'];
        $description = $row['description'];
        $lessons = $row['lessons'];
        $courseRatings = $row['course_ratings'];
        $thumbnail = $row['thumbnail'];

        // Define the base URL path to the thumbnails
        $thumbnailBaseUrl = "/UploadCourse/Course-Thumbnails";
        $thumbnailPath = "$thumbnailBaseUrl/$instructorEmail/$courseName/$thumbnail";

        // Set a default thumbnail if file does not exist
        if (!file_exists(__DIR__ . "/Course-Thumbnails/$instructorEmail/$courseName/$thumbnail")) {
            $thumbnailPath = 'default_thumbnail.png';
        }

        $coursesWithThumbnails[] = [
            'courseId' => $courseId, // Add courseId to the response
            'coursename' => $courseName,
            'description' => $description,
            'lessons' => $lessons,
            'difficulty' => $difficulty,
            'course_ratings' => $courseRatings,
            'thumbnail' => $thumbnailPath,
            'instructor' => $instructorNames[$instructorEmail] ?? 'Unknown Instructor'
        ];
    }
} else {
    echo json_encode(['error' => 'No courses found.']);
    exit;
}

echo json_encode($coursesWithThumbnails);



// Close the database connection
$conn->close();


?>
