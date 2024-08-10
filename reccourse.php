<?php
include 'conn.php';

header('Content-Type: application/json'); // Set content type to JSON for AJAX requests

function getInstructorName($conn, $email) {
    $query = "SELECT instructor_name FROM instructor WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return htmlspecialchars($row['instructor_name'], ENT_QUOTES, 'UTF-8');
    } else {
        return "Unknown Instructor";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['courseId']) || !is_numeric($_POST['courseId'])) {
        echo json_encode(["error" => "Invalid courseId."]);
        exit;
    }

    $courseId = intval($_POST['courseId']);

    // Prepare the SQL query to fetch the course details
    $query = "SELECT * FROM courses WHERE courseId = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo json_encode(["error" => "Prepare failed: " . htmlspecialchars($conn->error)]);
        exit;
    }

    $stmt->bind_param('i', $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Course not found."]);
        exit;
    }

    $course = $result->fetch_assoc();

    // Extract the relevant details from the $course array
    $courseName = htmlspecialchars($course['courseName'], ENT_QUOTES, 'UTF-8');
    $instructorEmail = htmlspecialchars($course['instructor_email'], ENT_QUOTES, 'UTF-8');
    $thumbnail = htmlspecialchars($course['thumbnail'], ENT_QUOTES, 'UTF-8');

    // Define the base URL path to the thumbnails
    $thumbnailBaseUrl = "/UploadCourse/Course-Thumbnails";
    $thumbnailPath = "$thumbnailBaseUrl/$instructorEmail/$courseName/$thumbnail";

    // Fetch instructor name
    $instructorName = getInstructorName($conn, $instructorEmail);

    echo json_encode([
        "courseName" => $courseName,
        "instructorName" => $instructorName,
        "difficulty" => htmlspecialchars($course['difficulty'], ENT_QUOTES, 'UTF-8'),
        "lessons" => htmlspecialchars($course['lessons'], ENT_QUOTES, 'UTF-8'),
        "description" => htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8'),
        "thumbnail" => $thumbnailPath
    ]);
    exit;
}

?>
