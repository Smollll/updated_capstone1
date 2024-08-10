<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "course_creation";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT courseId, courseName, difficulty, instructor, lessons, course_ratings, thumbnail, description FROM courses";
$result = $conn->query($sql);

$courses = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo json_encode(array("message" => "No courses found."));
    exit;
}

$conn->close();

echo json_encode($courses);
?>
