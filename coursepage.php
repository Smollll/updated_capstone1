<?php
include 'conn.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['courseId']) || !is_numeric($_POST['courseId'])) {
        die("Invalid courseId.");
    }

    $courseId = intval($_POST['courseId']);

    // Prepare the SQL query to fetch the course details
    $query = "SELECT * FROM courses WHERE courseId = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('i', $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Course not found.");
    }

    $course = $result->fetch_assoc();

    // Extract the relevant details from the $course array
    $courseName = htmlspecialchars($course['courseName']);
    $instructorEmail = htmlspecialchars($course['instructor_email']);
    $thumbnail = htmlspecialchars($course['thumbnail']);

    // Define the base URL path to the thumbnails
    $thumbnailBaseUrl = "/UploadCourse/Course-Thumbnails";
    $thumbnailPath = "$thumbnailBaseUrl/$instructorEmail/$courseName/$thumbnail";

    // Fetch instructor name
    $instructorQuery = "SELECT instructor_name FROM instructor WHERE email = ?";
    $instructorStmt = $conn->prepare($instructorQuery);

    if ($instructorStmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $instructorStmt->bind_param('s', $instructorEmail);
    $instructorStmt->execute();
    $instructorResult = $instructorStmt->get_result();

    if ($instructorResult->num_rows === 0) {
        $instructorName = "Unknown Instructor";
    } else {
        $instructorRow = $instructorResult->fetch_assoc();
        $instructorName = htmlspecialchars($instructorRow['instructor_name']);
    }

    // Assign session variables
    $_SESSION['learner_id'] = isset($_SESSION['learner_id']) ? $_SESSION['learner_id'] : 1; // Default or fetched learner ID
    $_SESSION['course_id'] = $courseId;

} else {
    die("Invalid request method.");
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="coursepage.css">

    <div class="container">
        <div class="thum">
            <img id="thumbnail" src="<?php echo $thumbnailPath; ?>" alt="Course Thumbnail">  
        </div>
        <div class="des">
            <h1 id="courseName" class="course-name"><?php echo $courseName; ?></h1>
            <br><br>
            <h2 id="instructorName" class="instructor-name"><?php echo "Instructor: " . $instructorName; ?></h2>
            
            <h3 id="difficulty" class="lig"><?php echo "Difficulty: " . htmlspecialchars($course['difficulty']); ?></h3>
            
            <h3 id="lessons" class="lig"><?php echo "Lessons: " . htmlspecialchars($course['lessons']); ?></h3>
        
            <div class="description">
                <h3>Description</h3>
                <p id="description"><?php echo htmlspecialchars($course['description']); ?></p>
            </div>
            
            <div class="course-obj">
                <h3>Course Objectives</h3>
                <ul>
                    <li class="obj">Objective 1</li>
                    <li class="obj">Objective 2</li>
                    <li class="obj">Objective 3</li>
                </ul>
            </div>
            <br>
            <div>
                <button class="enroll-button">Enroll Now!!</button>
            </div>
        </div>

        
 <div class="com">
    <input type="hidden" id="courseId" value="<?php echo htmlspecialchars($courseId); ?>">     

</div>
    </div>

    <div class="course-box">
        <div class="courses-container" id="courses-container"></div>
    </div>

    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

    function loadCourses() {
        $.ajax({
            url: 'fetch_homepage.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    $('#courses-container').html('<p>' + response.error + '</p>');
                } else {
                    $('#courses-container').empty();
                    response.forEach(course => {
                        const courseContainer = $('<div>').addClass('course-con');
                        const courseDiv = $('<div>').addClass('folder').attr('data-course-id', course.courseId);

                        const courseRatings = course.course_ratings; 
                        let ratingHtml = '<div class="star-rating">';
                        for (let i = 1; i <= 5; i++) {
                            let starType = 'empty'; 
                            if (i <= courseRatings) {
                                starType = 'full'; 
                            } else if (i === Math.floor(courseRatings) + 1 && courseRatings % 1 !== 0) {
                                starType = 'half'; 
                            }
                            
                            ratingHtml += `
                                <input type="radio" id="star${i}" name="rating" value="${i}" class="star-input" style="display: none;">
                                <label for="star${i}" class="star-label ${starType}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="clamp(1rem, 2vw, 1.75rem)" height="clamp(1rem, 2vw, 1.75rem)" viewBox="0 0 24 24" fill="${starType === 'full' ? '#ffb633' : '#e0e0e0'}">
                                        <path d="M12 2.3l2.4 7.4h7.6l-6 4.8 2.3 7.4-6.3-4.7-6.3 4.7 2.3-7.4-6-4.8h7.6z"/>
                                    </svg>
                                </label>`;
                        }
                        ratingHtml += '</div>';

                        courseDiv.html(`
                            <img src="${course.thumbnail}" alt="${course.coursename}" class="course-thumbnail">
                            <h3>${course.coursename}</h3>
                            <p>${course.difficulty}</p>
                            ${ratingHtml}
                        `);

                        courseDiv.click(function() {
                            const courseId = $(this).data('course-id'); // Use data() to get the data attribute
                            if (courseId) {
                                loadCourseDetails(courseId);
                                loadCourseComments(courseId); // Load comments for the clicked course
                            } else {
                                alert("Course ID is not defined.");
                            }
                        });

                        courseContainer.append(courseDiv);
                        $('#courses-container').append(courseContainer);
                    });
                }
            },
            error: function(xhr, status, error) {
                $('#courses-container').html('<p>There was an error fetching the courses.</p>');
                console.error('Error fetching courses:', error);
            }
        });
    }

    // Function to load course details
    function loadCourseDetails(courseId) {
        $.ajax({
            url: 'reccourse.php',
            type: 'POST',
            data: { courseId: courseId },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    $('#courseName').text(response.courseName);
                    $('#instructorName').text('Instructor: ' + response.instructorName);
                    $('#difficulty').text('Difficulty: ' + response.difficulty);
                    $('#lessons').text('Lessons: ' + response.lessons);
                    $('#description').text(response.description);
                    $('#thumbnail').attr('src', response.thumbnail).attr('alt', response.courseName);
                }
            },
            error: function(xhr, status, error) {
                alert('There was an error loading the course details.');
                console.error('Error loading course details:', error);
            }
        });
    }
    loadCourses();
});


$(document).ready(function() {
    // Get the course ID from the hidden input field
    var courseId = $('#courseId').val();
    
    // Call loadCourseComments if courseId is not null
    if (courseId) {
        loadCourseComments(courseId);
    }
});

function loadCourseComments(course_id) {
    $.ajax({
        url: 'fetch_reviews.php',
        type: 'POST',
        data: { course_id: course_id },
        dataType: 'json',
        success: function(response) {
            var commentsDiv = $('.com');
            commentsDiv.empty(); 

            if (response.length > 0) {
                $.each(response, function(index, comment) {
                    var commentHTML = '<div class="comment-item">';
                    commentHTML += '<div class="comment-header">';
                    /*commentHTML += '<img src="path-to-avatar" alt="avatar" class="comment-avatar">'; */
                    commentHTML += '<div class="comment-author-container">';
                    commentHTML += '<p class="comment-author">' + comment.learner_fullname + '</p>';
                    commentHTML += '<p class="comment-time">3 hours ago</p>'; // Placeholder for time
                    commentHTML += '</div>';
                    commentHTML += '</div>';
                    
                    commentHTML += '<p class="comment-text">' + comment.review + '</p>';
                    commentHTML += '<div class="comment-actions">';
                    commentHTML += '<button class="like-button">👍</button>';
                    commentHTML += '<button class="dislike-button">👎</button>';
                    commentHTML += '<button class="reply-button" data-comment-id="' + comment.course_reviews_id + '">Reply</button>';
                    commentHTML += '</div>';

                    // Add a "See Replies" button if there are replies
                    if (comment.replies && comment.replies.length > 0) {
                        commentHTML += '<button class="see-replies-button">See Replies (' + comment.replies.length + ')</button>';
                        commentHTML += '<div class="replies-container" style="display: none;">';
                        $.each(comment.replies, function(replyIndex, reply) {
                            commentHTML += '<div class="reply-item">';
                            commentHTML += '<div class="reply-header">';
                            commentHTML += '<div class="reply-author-container">';
                            commentHTML += '<p class="reply-author">You</p>';
                            commentHTML += '<p class="reply-time">Just now</p>'; // Placeholder for reply time
                            commentHTML += '</div>';
                            commentHTML += '</div>';
                            commentHTML += '<p class="reply-text">' + reply + '</p>'; // Assuming `reply` has `reply_review`
                            commentHTML += '</div>';
                        });
                        commentHTML += '</div>'; // Close replies-container
                    }

                    commentHTML += '</div>'; // Close comment-item

                    commentsDiv.append(commentHTML);
                });

                // Event delegation to handle "See Replies" button click
                commentsDiv.off('click', '.see-replies-button').on('click', '.see-replies-button', function() {
                    var $button = $(this);
                    var $repliesContainer = $button.siblings('.replies-container');
                    if ($repliesContainer.is(':visible')) {
                        $repliesContainer.hide();
                        $button.text('See Replies (' + $repliesContainer.find('.reply-item').length + ')');
                    } else {
                        $repliesContainer.show();
                        $button.text('Hide Replies');
                    }
                });

            } else {
                commentsDiv.append('<p>No comments available for this course.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

$(document).on('click', '.reply-button', function() {
    var commentId = $(this).data('comment-id');
    var commentItem = $(this).closest('.comment-item');
    var repliesContainer = commentItem.find('.replies-container');
    var seeRepliesButton = commentItem.find('.see-replies-button');

    // Hide all existing reply forms
    $('.reply-form').remove();

    // Insert the reply form before the "See Replies" button
    seeRepliesButton.before(`
        <div class="reply-form">
            <textarea placeholder="Write a reply..." required></textarea>
            <div class="reply-buttons">
                <button class="cancel-reply">Cancel</button>
                <button class="submit-reply" data-comment-id="${commentId}">Submit Reply</button>
            </div>
        </div>
    `);
});

$(document).on('click', '.cancel-reply', function() {
    // Remove the reply form
    $(this).closest('.reply-form').remove();
});

//ajax to submit reply


$(document).on('click', '.submit-reply', function() {
    var $button = $(this); // Store reference to the button
    var commentId = $button.data('comment-id');
    var $replyForm = $button.closest('.reply-form');
    var replyText = $replyForm.find('textarea').val();

    // Ensure both values are present
    if (commentId && replyText) {
        // Make AJAX request to submit the reply
        $.ajax({
            url: 'submit_reply.php',
            method: 'POST',
            data: {
                comment_id: commentId,
                reply: replyText
            },
            success: function(response) {
                // Assuming the response is the newly added reply text or confirmation
                var newReplyHTML = '<div class="reply-item">' +
                                   '<div class="reply-header">' +
                                   '<p class="reply-author">You</p>' +
                                   '</div>' +
                                   '<p class="reply-text">' + replyText + '</p>' +
                                   '</div>';

                // Append the new reply to the appropriate comment's replies container
                $button.closest('.comment-item').find('.replies-container').append(newReplyHTML);

                // Clear the reply form
                $replyForm.remove();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert('Failed to submit reply.');
            }
        });
    } else {
        alert('Comment ID or reply text is missing.');
    }
});

    
    </script>
