<?php
session_start();
include 'conn.php';

// Retrieve the user's email from the session
$email = $_SESSION['email'] ?? 'default_user';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Query to fetch courses
    $stmt = $conn->prepare("SELECT coursename, instructor, thumbnail, description, lessons FROM courses WHERE instructor_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    echo json_encode($courses);
    $stmt->close();
    $conn->close();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseName = $_POST['courseName'];
    $numberOfLessons = $_POST['NumberofLessons'];
    $courseDescription = $_POST['courseDescription'];
    $difficulty = $_POST['difficulty'];
    $thumbnail = $_FILES['thumbnail'] ?? null;

    // Define the target directory for the course
    $courseDir = __DIR__ . "/courses/" . $email . "/" . $courseName;

    // Check if the course already exists in the user's folder
    if (is_dir($courseDir)) {
        echo "Error: The course '$courseName' already exists in your folder.";
        exit;
    }

    // Check if file was uploaded
    if ($thumbnail && $thumbnail['error'] == UPLOAD_ERR_OK) {
        // Define the base directory for course thumbnails
        $thumbnailBaseDir = __DIR__ . "/Course-Thumbnails/" . $email . "/" . $courseName;
        
        // Create directories if they don't exist
        if (!is_dir($courseDir)) {
            mkdir($courseDir, 0777, true);
        }
        if (!is_dir($thumbnailBaseDir)) {
            mkdir($thumbnailBaseDir, 0777, true);
        }

        // Define the new filename
        $fileExtension = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
        $newFileName = $courseName . "." . $fileExtension;
        $targetFilePath = $thumbnailBaseDir . "/" . $newFileName;

        // Move the uploaded file
        if (move_uploaded_file($thumbnail['tmp_name'], $targetFilePath)) {
            // Check if the email exists in the instructors table
            $checkEmailStmt = $conn->prepare("SELECT email FROM instructor WHERE email = ?");
            $checkEmailStmt->bind_param("s", $email);
            $checkEmailStmt->execute();
            $checkEmailStmt->store_result();
            
            if ($checkEmailStmt->num_rows === 0) {
                die("Error: The instructor email does not exist in the instructors table.");
            }
            $checkEmailStmt->close();

            // Insert into database with thumbnail file name
            $stmt = $conn->prepare("INSERT INTO courses (instructor_email, courseName, difficulty, lessons, description, thumbnail) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $email, $courseName, $difficulty, $numberOfLessons, $courseDescription, $newFileName);
            
            if ($stmt->execute()) {
                // Create lesson folders
                for ($i = 1; $i <= $numberOfLessons; $i++) {
                    $lessonDir = $courseDir . "/Lesson " . $i;
                    if (!is_dir($lessonDir)) {
                        mkdir($lessonDir, 0777, true);
                    }
                }

                echo "Course '$courseName' created successfully with $numberOfLessons lessons!";
            } else {
                echo "Error inserting course into database: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "File upload error: " . ($thumbnail ? $thumbnail['error'] : 'No file uploaded');
    }

    $conn->close();
}
?>
