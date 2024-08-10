<?php
include 'conn.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['comment_id']) || !is_numeric($_POST['comment_id']) ||
        !isset($_POST['reply']) || empty($_POST['reply'])) {
        die("Invalid input.");
    }

    $comment_id = intval($_POST['comment_id']);
    $reply_text = $_POST['reply'];

    // Get learner ID from session
    $learner_id = isset($_SESSION['learner_id']) ? intval($_SESSION['learner_id']) : 0;

    // Check if learnerId is valid
    if ($learner_id === 0) {
        die("Invalid learner ID.");
    }

    // Prepare and execute SQL query to insert a new reply
    $stmt = $conn->prepare("INSERT INTO comment_reply (course_reviews_id, reply_review) VALUES (?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("is", $comment_id, $reply_text);

    if ($stmt->execute()) {
        echo "Reply submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
