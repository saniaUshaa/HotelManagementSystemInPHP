<?php
// Ensure the session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the admin name from the session
$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : "Admin";
?>


<?php require 'partials/verticalnav_css.php' ?>
<div class="wrapper">
    <aside id="sidebar">
        <div class="d-flex">
            <button class="toggle-btn" type="button">
                <i class="fa-solid fa-hotel fa-2x"></i>
            </button>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="/DBProject/manage.php" class="sidebar-link">
                    <i class="lni lni-user"></i>
                    <span>User</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/DBProject/manageHotel.php" class="sidebar-link">
                    <i class="fa-solid fa-archway"></i>
                    <span>Manage Hotels</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/DBProject/manageEmployees.php" class="sidebar-link">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Manage Employees</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/DBProject/manageCustomer.php" class="sidebar-link">
                    <i class="fa-solid fa-users"></i>
                    <span>Manage Guests</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/DBProject/allData.php" class="sidebar-link">
                    <i class="fa-solid fa-database"></i>
                    <span>Show Database</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <a href="/DBProject/logout.php" class="sidebar-link">
                <i class="lni lni-exit"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>
    <div class="main p-4">
        <!-- Welcome Heading -->
        <div class="text-center mb-4">
            <h1>Welcome to Your Profile</h1>
            <p class="text-muted">Explore your account details and manage your profile settings.</p>
        </div>
        <!-- Content Layout -->
        <div class="row">
            <!-- Full-Width First Image -->
            <div class="col-12 text-center mb-4">
                <img src="imgs/u1.jpg" alt="Profile Image"  style="width: 542px; height: 200px;">
            </div>
        </div>
        <div class="row">
            <div class="content">
                <h1 class="text-center mb-4">Admin Dashboard</h1>
                <?php
                //fetching data
                require 'partials/connectDB.php';
                $recentBookings = $conn->query("SELECT COUNT(*) AS count FROM Booking WHERE B_TIME >= NOW() - INTERVAL 1 DAY")->fetch_assoc()['count'];
                $totalBookings = $conn->query("SELECT COUNT(*) AS count FROM Booking")->fetch_assoc()['count'];
                $totalCustomers = $conn->query("SELECT COUNT(*) AS count FROM Customer")->fetch_assoc()['count'];
                $totalReviews = $conn->query("SELECT COUNT(*) AS count FROM Feedback")->fetch_assoc()['count'];
                $totalHotels = $conn->query("SELECT COUNT(*) AS count FROM Hotel")->fetch_assoc()['count'];
                $avgRooms = $conn->query("SELECT ROUND(AVG(room_count),0) AS avgcount FROM (SELECT COUNT(ROOM_NO) AS room_count FROM Hotel_Room GROUP BY HL_ID) AS HR;")->fetch_assoc()['avgcount'];
                $totalSignin = $conn->query("SELECT COUNT(*) AS count FROM SIGNIN")->fetch_assoc()['count'];
                ?>
                <div class="row mb-4 text-center" style="display:flex;align-items:center;justify-content:center;">
                <div class="col-md-3">
                    <div class="card p-3">
                    <h5>Recent Bookings</h5>
                    <p class="display-6 text-success"><?php echo $recentBookings; ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                    <h5>Total Sigin</h5>
                    <p class="display-6 text-primary"><?php echo $totalSignin; ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                    <h5>Total Hotels</h5>
                    <p class="display-6 text-primary"><?php echo $totalHotels; ?></p>
                    </div>
                </div>
                </div>

                <!-- Booking Analytics Section -->
                <h2 class="section-title">Hotel Analytics</h2>
                <div class="row mb-4 text-center" style="display:flex;align-items:center;justify-content:center;">
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Total Hotels</h5>
                        <p class="display-6"><?php echo $totalHotels; ?></p>
                    </div>
                    </div>
                <div class="col-md-4">
                    <div class="card p-3">
                    <h5>Average Rooms per Hotel</h5>
                    <p class="display-6"><?php echo $avgRooms; ?></p>
                    </div>
                </div> 
                </div>

                <!-- User Analytics Section -->
                <h2 class="section-title">User, Bookings, Reviews Analytics</h2>
                <div class="row mb-4 text-center" style="display:flex;align-items:center;justify-content:center;">
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Total Bookings</h5>
                        <p class="display-6"><?php echo $totalBookings; ?></p>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                    <h5>Registrations</h5>
                    <p class="display-6"><?php echo $totalCustomers; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                    <h5>Reviews</h5>
                    <p class="display-6"><?php echo $totalReviews; ?></p>
                    </div>
                </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script>
    const hamBurger = document.querySelector(".toggle-btn");
    hamBurger.addEventListener("click", function () {
        document.querySelector("#sidebar").classList.toggle("expand");
    });
</script>
