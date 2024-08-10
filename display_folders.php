<?php
session_start();
include 'conn.php';
$email = $_SESSION['email'] ?? 'default_user';
// Retrieve the user's name from the database
$retreive_name = "SELECT instructor_name FROM instructor WHERE email = ?";
$stmt = $conn->prepare($retreive_name);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $name = $result->fetch_assoc()['instructor_name'];
    $_SESSION['name_instructor'] = $name;
} else {
    $name = null;
    echo "Error: Instructor not found.";
    exit;
}

$stmt->close();

// Define the path to the user's folder
$userFolderPath = __DIR__ . "/courses/$email";

// Fetch course details from the database
$sql = "SELECT courseName, difficulty, description, lessons, course_ratings, thumbnail FROM courses WHERE instructor_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($coursename, $difficulty, $description, $lessons, $course_ratings, $thumbnail);

// Prepare an associative array to hold course details
$courses = [];
while ($stmt->fetch()) {
    $courses[$coursename] = [
        'description' => $description,
        'lessons' => $lessons,
        'difficulty' => $difficulty,
        'course_ratings' => $course_ratings,
        'thumbnail' => $thumbnail
    ];
}

$stmt->close();

// Define the base URL path to the thumbnails
$thumbnailBaseUrl = "/UploadCourse/Course-Thumbnails";

// Check if the directory exists
if (is_dir($userFolderPath)) {
    // Open the directory
    $dir = opendir($userFolderPath);

    // Display the folders in the user's directory
    while (($file = readdir($dir)) !== false) {
        // Skip the current (.) and parent (..) directories
        if ($file != '.' && $file != '..') {
            // Fetch the corresponding course details
            $courseDetails = $courses[$file] ?? ['description' => 'No description available', 'lessons' => '0', 'difficulty' => 'Unknown', 'course_ratings' => 0, 'thumbnail' => ''];

            // Check if this is the selected course
            $isSelected = ($file === ($_SESSION['selected_course'] ?? '')) ? 'selected' : '';
            $fullStars = floor($courseDetails['course_ratings']); // Get the integer part of the rating
            $halfStar = ceil($courseDetails['course_ratings']) - $fullStars; // Check if there's a half star

            // Define the path to the thumbnail
            $thumbnailPath = "$thumbnailBaseUrl/$email/$file/{$courseDetails['thumbnail']}";

            // Check if the thumbnail file exists
            if (!file_exists(__DIR__ . "/Course-Thumbnails/$email/$file/{$courseDetails['thumbnail']}")) {
                $thumbnailPath = 'default_thumbnail.png'; // Set a default thumbnail if file does not exist
            }

            // Display the folder with course details inside a form
            echo "<form method='post' action='actions/view_folder.php?coursename=$file'>";
            echo "<button class='folder-button $isSelected' name='viewfolder' type='submit id='viewfolder'>";
            echo "<input type='hidden' name='coursename' value='$file'>";
            echo "<input type='hidden' name='email' value='$email'>";
            echo "<div class='folder'>";
            echo "<img src='$thumbnailPath' alt='Course Thumbnail' class='course-thumbnail'>";
            echo "<h3>$file</h3>";
            echo "<div class='star-rating'>";
            for ($i = 1; $i <= 5; $i++) {
                $starType = 'empty'; // Default to empty star

                // Determine if current star should be full, half, or empty
                if ($i <= $fullStars) {
                    $starType = 'full'; // Full star
                } elseif ($i == $fullStars + 1 && $halfStar > 0) {
                    $starType = 'half'; // Half star
                }

                // SVG path for star icon based on starType
                $starSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="clamp(1rem, 2vw, 1.75rem)" height="clamp(1rem, 2vw, 1.75rem)" viewBox="0 0 24 24" fill="#ffb633">
                    <path d="M12 2.3l2.4 7.4h7.6l-6 4.8 2.3 7.4-6.3-4.7-6.3 4.7 2.3-7.4-6-4.8h7.6z"/>
                </svg>';

                echo "
                <input type='radio' id='star{$i}' name='rating' value='{$i}' class='star-input' style='display: none;'>
                <label for='star{$i}' class='star-label {$starType}'>{$starSvg}</label>
                ";
            }
            echo $courseDetails['course_ratings'];
            echo "</div>";
            echo "<p>Difficulty: {$courseDetails['difficulty']}</p>";
            echo "<p>Number of Lessons: {$courseDetails['lessons']}</p>";
            echo "<p>Description: {$courseDetails['description']}</p>";
            echo "</div>";
            echo "</button>";
            echo "</form>";
        }
    }

    // Close the directory
    closedir($dir);
} else {
    echo "<div class='NoFolders'>";
    echo "<p>No courses found for $name</p>";
    echo "</div>";
}

// Close the database connection
$conn->close();

// Indicate that display_folders.php was included
echo "<script>var isFoldersDisplayed = true;</script>";
?>
