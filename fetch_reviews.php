<?php
// Database connection
include 'conn.php';

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Fetch comments (without the reply_review column)
    $sql = "SELECT cr.course_reviews_id, cr.review, cr.ratings, tl.learner_fullname
            FROM course_reviews cr
            JOIN test_learners tl ON cr.learner_id = tl.learner_id
            WHERE cr.course_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    
    while ($row = $result->fetch_assoc()) {
        $comment_id = $row['course_reviews_id'];
        
        // Fetch replies for each comment from the comment_reply table
        $reply_sql = "SELECT reply_review
                      FROM comment_reply
                      WHERE course_reviews_id = ?";
        $reply_stmt = $conn->prepare($reply_sql);
        $reply_stmt->bind_param("i", $comment_id);
        $reply_stmt->execute();
        $reply_result = $reply_stmt->get_result();

        $replies = [];
        while ($reply_row = $reply_result->fetch_assoc()) {
            $replies[] = $reply_row['reply_review'];
        }

        $row['replies'] = $replies; // Attach replies to the comment
        $comments[] = $row;
    }

    $stmt->close();
    $conn->close();
    
    echo json_encode($comments);
}
?>
