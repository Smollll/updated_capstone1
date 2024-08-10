<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>

    <link rel="stylesheet" href="hompage.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body>
<header class="header">
        <nav class="navigation">
            <div class="nav-links">
                <a href="#" class="nav-link">Home</a>
                <a href="#" class="nav-link" id="coursesLink">My Learning</a>
                <a href="#" class="nav-link">History</a>
            </div>
            <div class="nav-icons">
                <box-icon name='bell' color='#fff' type="solid" size="max(1.5vw, 20px)" class="bell-icon nav-icon"></box-icon>
                <box-icon name='user-circle' color='#fff' type="solid" size="max(1.5vw, 20px)" onclick="togglePopup(event)" class="nav-icon"></box-icon>

                <!-- Popup Profile -->
                <div class="popup" id="popup">
                    <div class="popup-content">
                        <box-icon name='x' class="close" onclick="closePopup(event)"></box-icon>
                        <div class="userpicname">
                            <div class="userpic"></div>
                            <div class="username"><?php echo $name;?></div>
                        </div>
                        <hr class="divider">
                        <div class="settings">
                            <div class="editprofile" onclick="OpenandCloseEdit(event)">
                                <box-icon name='edit' color='#000' class="settingsIcons" onclick="OpenandCloseEdit(event)"></box-icon>
                                <p class="IconLink" onclick="OpenandCloseEdit(event)">Edit Profile</p>
                            </div>

                            <div class="lightdark">
                                <button class="light">
                                    <box-icon name='sun' type='solid' color='#ffad00' class="settingsIcons sun"></box-icon>
                                    <p class="IconText">Light</p>
                                </button>
                                <button class="dark">
                                    <box-icon name='moon' type='solid' color='#000' class="settingsIcons moon"></box-icon>
                                    <p class="IconText">Dark</p>
                                </button>
                            </div>

                            <div class="exitdashboard">
                                <box-icon name='exit' color='#000' type='solid' class="settingsIcons"></box-icon>
                                <a class="IconLink" href="#">Exit Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Popup -->
            </div>
        </nav>
</header>

<div class="logo"><span class="Agri">Agri</span><span>Learn</span></div>

<div class="container">
    <!-- Search container -->
    <div class="search-container">
        <div class="wrap">
            <input type="text" id="search-box" placeholder="Search...">
            <box-icon id="search-icon" name="search-alt-2"></box-icon>
            <div class="dropdown">
                <box-icon class="sortIcon" id="sortIcon" name='sort-up'></box-icon>
                <div class="dropdown-content">
                    <button class="dropdown-option">Newest to Oldest</button>
                    <button class="dropdown-option">Oldest to Newest</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses container -->'
    <form action="coursepage.php" method="post" id="courseForm">
    <div class="course-box">
        <div class="courses-container" id="courses-container">
            <!-- Course elements go here -->
        </div>
    </div>
    <input type="hidden" name="courseId" id="courseId">
</form>

<br><br>
<h1 class="rec">RECOMMENDED INSTRUCTORS FOR THE MONTH OF JULY</h1>

<div class="probox">
    <div class="ins"></div>
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
                        const courseId = $(this).data('course-id');
                        if (courseId) {
                            $('#courseId').val(courseId);
                            $('#courseForm').attr('action', 'coursepage.php').submit(); // Redirect to the comments page
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
    function loadCourseComments(course_id) {
        $.ajax({
            url: 'fetch_reviews.php',
            type: 'POST',
            data: { course_id: course_id },
            dataType: 'json',
            success: function(response) {
                var commentsDiv = $('.com');
                commentsDiv.empty(); 

                if(response.length > 0) {
                    $.each(response, function(index, comment) {
                        var commentHTML = '<div class="comment-item">';
                        commentHTML += '<h4>Review ID: ' + comment.course_reviews_id + '</h4>';
                        commentHTML += '<p>Reviewer: ' + comment.learner_fullname + '</p>';
                        commentHTML += '<p>Review: ' + comment.review + '</p>';
                        commentHTML += '<p>Rating: ' + comment.ratings + '</p>';
                        commentHTML += '</div>';
                        commentsDiv.append(commentHTML);
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

   
    loadCourses();

    document.getElementById('sortIcon').addEventListener('click', function() {
        const dropdown = this.parentNode;
        const icon = this;
        dropdown.classList.toggle('open');
        if (dropdown.classList.contains('open')) {
            icon.setAttribute('name', 'sort-down');
        } else {
            icon.setAttribute('name', 'sort-up');
        }
    });
});


</script>
</body>
</html>
