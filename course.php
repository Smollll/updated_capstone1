<?php
// Retrieve the user's name from the session
session_start();
include 'conn.php';

$emailcomp = $_SESSION['email'] = 'john@gmail.com';
$name = $_SESSION['instructor_name'] ?? 'default_user';
$phone = $_SESSION['contact_no'] ?? 'default_user';
$address = $_SESSION['address'] ?? 'default_user';
$stmt = $conn->prepare("SELECT instructor_name, contact_no, address FROM instructor WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="nav.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<!-- Navigation -->
    <header class="header">
        <nav class="navigation">
            <div class="nav-links">
            <a href="Dashboard.php" class="nav-link">Dashboard</a>
            <a href="#" class="nav-link" id="coursesLink">Courses</a>
            <a href="#" class="nav-link">History</a>
            </div>
        <div class="nav-icons">
            <box-icon name='bell' color='#fff' type="solid" size="max(1.5vw, 20px)" class="bell-icon" class="nav-icon"></box-icon>
            <box-icon name='user-circle' color='#fff' type="solid" size="max(1.5vw, 20px)" onclick="togglePopup(event)"class="nav-icon"></box-icon>

            <!-- Popup Profile -->
            <div class="popup" id="popup">
                <div class="popup-content">
                <box-icon name='x'class="close" onclick="closePopup(event)"></box-icon>
                    <div class="userpicname">
                        <div class="userpic"></div>
                        <div class="username"><?php echo $name;?></div>
                    </div>
                    <hr class="divider">
                    <div class="settings">
                        <div class="editprofile" onclick="OpenandCloseEdit(event)"><box-icon name='edit' color='#000' class="settingsIcons" onclick="OpenandCloseEdit(event)"></box-icon><p class="IconLink" onclick="OpenandCloseEdit(event)">Edit Profile</p></div>

                        <div class="lightdark">
                           <button class="light"><box-icon name='sun' type='solid' color='#ffad00' class="settingsIcons sun"></box-icon><p class="IconText">Light</p></button>
                           <button class="dark"><box-icon name='moon' type='solid' color='#000' class="settingsIcons moon"></box-icon><p class="IconText">Dark</p></button>
                        </div>

                        <div class="exitdashboard">
                            <box-icon name='exit' color='#000' type='solid'class="settingsIcons"></box-icon><a class="IconLink" href="#">Exit Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Popup -->

        </nav>
    </header>
<!-- End of Navigation -->
    <div class="popupEdit" id="popupEdit">
        <div class="edit-content">
            <div class="closeandlogo">
                <div class="Popuplogo"><span class="Agri">Agri</span><span>Learn Instructor</span></div>
                <box-icon name='x'class="closePopup" onclick="hideModal(event)"></box-icon>
            </div>
            <div class="edit-form">
            <form action="" method="post" class="editForm" id="editForm">
                <div class="userpicnameemail">
                    <div class="Edit-userpic"></div>
                    <div class="usernameemail">
                        <input type="text" name="id" id="id" value="<?php echo $id; ?>" hidden>
                        <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $name; ?>" readonly>
                        <div class="inputs">
                            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" readonly>
                            <box-icon name='edit' type='solid' data-field="email" class="editicon" size="1rem"></box-icon>
                        </div>
                    </div>
                </div>
                <div class="addressphone">
                    <div class="inputs">
                        <input type="text" name="address" id="address" placeholder="Address" value="<?php echo $address; ?>" readonly>
                        <box-icon name='edit' type='solid' data-field="address" class="editicon" size="1rem"></box-icon>
                    </div>
                    <div class="inputs">
                        <input type="text" name="phone" id="phone" placeholder="Phone" value="<?php echo $phone; ?>" readonly>
                        <box-icon name='edit' type='solid' data-field="phone" class="editicon" size="1rem"></box-icon>
                    </div>
                </div>
                <div class="submit-edit">
                    <button type="submit" class="submitEdit" disabled id="submitEdit">Submit</button>
                </div>
            </form>

            </div>
        </div>
    </div>
    <div id="popupOverlay" class="modal-overlay"></div>
<!-- Start of Content -->
    <div class="content" id="content">
        <div class="box1">
            <div class="logo"><span class="Agri">Agri</span><span>Learn</span></div>

            <div class="box1-buttons">
                <button class="add-course" id="add-course"><box-icon type='solid' name='book-alt'></box-icon>Upload Course</button>
                <button class="archives" id="archives"><box-icon name='trash' type='solid' ></box-icon>Archives</button>
            </div>

            <div class="storage-info">
                <p>Storage</p>
                <div class="progress-bar">
                    <div class="progress" style="width: 50%;"></div> <!-- Example: 50% progress -->
                </div>
                <p><span id="used-storage"></span> of 15 GB used</p>
            </div>

        </div>

        <div class="box2">
            <h1>Courses</h1>
            <!-- Box2 Header -->
                
            
            <!-- Search -->
            <div class="search-container">
                <button type="button" class="search-button">
                    <span class="search-icon">&#128269;</span> <!-- This is the Unicode for a search icon -->
                </button>
                <input type="text" placeholder="Search...">
            </div>
            <!-- End of Search -->

            <div class="videolessonaddcourse">
                <!-- Start of Video Lessons -->
                <div class="videoLessonText">
                    <p>Video Lessons</p>
                    <box-icon name='play-circle'></box-icon>
                </div>
                <!-- End of Course Upload -->

                <!-- Start of Add Course Icon and Text -->
                <div class="addcourseicon">
                    <div class="addcourseText">
                        <p style="cursor: pointer">Add Course</p>
                    </div>
                    
                    <box-icon name='plus-circle' class="addcourse" color='#dbb970' onclick="OpenCreateCourse(event)"></box-icon>
                    
                </div>

                </div>
            <!-- End of Box2 Header -->

            <div class="courseCreation" id="courseCreation">
                <div class="courseCreate" id="courseCreate">
                    <h2>Create Course</h2>
                    <form action="" method="post" id="courseForm">
                        <label for="thumbnail" id="thumbnail">Course Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" required>
                        <label for="courseName">Course Name:</label>
                        <input type="text" id="courseName" name="courseName" required>
                        <label for="difficulty" id="difficulty">Difficulty</label>
                        <select name="difficulty" id="difficulty">
                            <option value="Begginner">Begginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Expert">Expert</option>
                        </select>
                        <label for="NumberofLessons"> Number of Lessons:</label>
                        <input type="number" id="NumberofLessons" name="NumberofLessons" required>
                        <label for="courseDescription">Course Description:</label>
                        <textarea id="courseDescription" name="courseDescription" required></textarea>
                        <div class="form-buttons">
                            <button type="submit" class="submitCourse">Create</button>
                            <button type="button" class="cancelSubmit" onclick="OpenCreateCourse(event)">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Start of Box2 Content -->
            <div class="box2-content" id="box2-content">
                
            </div>

        </div>

    </div>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.pathname.includes('course.php')) {
                document.getElementById('add-course').classList.add('button-clicked');
            }else{
                document.getElementById('add-course').classList.remove('button-clicked');
            }
        });
      $(document).ready(function() {
    // Load courses on page load
        loadCourses();

        // Handle course form submission
            $('#courseForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: 'addcourse.php', // PHP script to handle the request
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.startsWith("Error:")) {
                        // Show error message for duplicate course name or other issues
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    } else {
                        // Success message for course creation
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response
                        }).then(() => {
                            // Reload the courses and close the popup
                            loadCourses();
                            $('#courseCreation').hide();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error creating the course. Please try again.'
                    });
                }
            });
        });
    });

    // Function to load courses
    function loadCourses() {
        $.ajax({
            url: 'display_folders.php', // PHP script to fetch courses
            type: 'GET',
            success: function(response) {
                $('#box2-content').html(response);
            },
            error: function() {
                $('#box2-content').html('<p>There was an error fetching the courses.</p>');
            }
        });
    }

    function togglePopup(event) {
        document.getElementById('popup').classList.toggle('show');
    }

    function closePopup(event) {
        document.getElementById('popup').classList.remove('show');
    }
    function OpenCreateCourse(event) {
        event.stopPropagation();
        var OpenCreateCourse = document.getElementById('courseCreation');
        OpenCreateCourse.style.display = OpenCreateCourse.style.display === 'none' ? 'flex' : 'none';
    }
   
    function OpenandCloseEdit(event) {
            event.stopPropagation();
            var modal = document.getElementById('popupEdit');
            if (modal.style.display === 'none' || modal.style.display === '') {
                showModal();
                document.getElementById('popup').style.display = 'none';
            } else {
                hideModal();
            }
        }

        function showModal() {
            $('#popupEdit').addClass('active');
            $('#popupOverlay').addClass('active');
        }

        function hideModal() {
            $('#popupEdit').removeClass('active');
            $('#popupOverlay').removeClass('active');
        }
        function enabledInputsandButtons(event) {
            var phone = document.getElementById('phone');
            var email = document.getElementById('email');
            var address = document.getElementById('address');
            var submitEdit = document.getElementById('submitEdit');

            // Get the clicked element to determine which field to enable
            var fieldToEdit = event.target.dataset.field;

            // Remove 'readonly' and add 'editable' class only for the selected field
            if (fieldToEdit === 'phone') {
                phone.removeAttribute('readonly');
                phone.classList.add('editable');
            } else if (fieldToEdit === 'email') {
                email.removeAttribute('readonly');
                email.classList.add('editable');
            } else if (fieldToEdit === 'address') {
                address.removeAttribute('readonly');
                address.classList.add('editable');
            }

            // Always enable the submit button
            submitEdit.removeAttribute('disabled');
        }

        // Attach the event listener to the edit icons with specific field data
        document.querySelectorAll('.editicon').forEach(icon => {
            icon.addEventListener('click', enabledInputsandButtons);
        });
        function resetForm() {
            var phone = document.getElementById('phone');
            var email = document.getElementById('email');
            var address = document.getElementById('address');
            var submitEdit = document.getElementById('submitEdit');

            // Reapply readonly and remove the editable class from all fields
            phone.setAttribute('readonly', true);
            email.setAttribute('readonly', true);
            address.setAttribute('readonly', true);
            phone.classList.remove('editable');
            email.classList.remove('editable');
            address.classList.remove('editable');

            // Disable the submit button
            submitEdit.setAttribute('disabled', true);
        }
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: 'update-info.php',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response
                    }).then((result) => {
                        if(result.isConfirmed) {
                          hideModal();
                          resetForm();
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error updating the information. Please try again.'
                    });
                }
            });
        });
    function togglePopup(event) {
        event.stopPropagation();
        var popup = document.getElementById('popup');
        popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    }

    function closePopup(event) {
        event.stopPropagation();
        var popup = document.getElementById('popup');
        popup.style.display = 'none';
    }

    let previousStorageSize = null;
        // Function to fetch and update storage information
        async function fetchAndCalculateStorage() {
            try {
                const response = await fetch('storageCalc.php');
                if (!response.ok) {
                    throw new Error('Failed to fetch storage data');
                }
                const data = await response.json();
                const storageUsed = data.size;
                const unit = data.unit;
                let storageUsedDisplay;
                let storageUsedInGB;

                // Ensure the displayed storage does not exceed 15 GB
                if (unit === 'GB') {
                    storageUsedInGB = storageUsed;
                    // Display storage as MB if less than 1 GB
                    storageUsedDisplay = storageUsedInGB < 1 
                        ? Math.floor(storageUsedInGB * 1000) + ' MB' 
                        : storageUsedInGB.toFixed(2) + ' GB';
                } else if (unit === 'MB') {
                    storageUsedInGB = storageUsed / 1000; // Convert MB to GB
                    // Display storage as MB if less than 1 GB
                    storageUsedDisplay = storageUsedInGB < 1 
                        ? (storageUsed).toFixed(2) + ' MB' 
                        : storageUsedInGB.toFixed(2) + ' GB';
                } else {
                    throw new Error('Unknown unit type');
                }

                // Update display only if there's a change in storage size
                if (storageUsed !== previousStorageSize) {
                    updateStorageUsed(storageUsedDisplay, storageUsedInGB);
                    previousStorageSize = storageUsedInGB; // Update previous storage size
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                document.getElementById('used-storage').textContent = 'Error fetching data';
            }
        }

        // Function to update storage display and progress bar
        function updateStorageUsed(storageUsedDisplay, storageUsedInGB) {
            const progressBar = document.querySelector('.progress');
            const usedStorageSpan = document.getElementById('used-storage');

            // Display the storage used with proper formatting
            usedStorageSpan.textContent = `${storageUsedDisplay} used`;

            // Calculate progress bar width based on GB
            const maxStorageGB = 15; // Maximum storage in GB
            const progressWidth = (storageUsedInGB / maxStorageGB) * 100;

            // Set progress bar width, ensuring it doesn't exceed 100%
            progressBar.style.width = `${Math.min(progressWidth, 100)}%`;
        }

        // Example usage: Initial fetch and calculation of storage used
        fetchAndCalculateStorage();

        // Automatically fetch and update storage information every 5 minutes
        setInterval(fetchAndCalculateStorage, 5 * 60 * 1000); // Update every 5 minutes
    </script>
</body>
</html>