<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != "true"){
    header("location:login.php");
    exit();

}
$adminName = $_SESSION['username'];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accounts - <?php echo $_SESSION['username']  ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            overflow-x: hidden;
            margin: 0;
            height: 100%; /* Ensure full height for centering */
        }

        .navbar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #343a40;
            padding-top: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }

        .navbar a:hover {
            background-color: #495057;
        }

        .content { 
            padding: 5px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            margin-top:5px;
        }

        .dashboard-container {
            width: 100%;
            max-width: 1200px; /* Limit max width */
            text-align: center;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 15px 0;
        }

        .card h5 {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card .display-6 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
  </head>
  <body>
    <?php require 'partials/verticalnav_css.php'?>

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
                    <a href="/DBProject/manageRooms.php" class="sidebar-link">
                        <i class="fa-solid fa-bed"></i>
                        <span>Rooms</span>
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
          <div class="text-center mb-4">
              <h1>Welcome to Your Profile</h1>
              <p class="text-muted">Explore your account details and manage your profile settings.</p>
          </div>
          <div class="row">
              <div class="col-12 text-center mb-4">
                  <img src="imgs/u1.jpg" alt="Profile Image"  style="width: 542px; height: 200px;">
              </div>
          </div>
          <div class="content">
            <div class="dashboard-container col-12 text-center mb-4">
            <div class="text-center mb-4">
              <h1>Admin Dashboard</h1>
          </div>
              <?php
              // fetching data
              require 'partials/connectDB.php';
              $recentBookings = $conn->query("SELECT COUNT(*) AS count FROM Booking WHERE B_TIME >= NOW() - INTERVAL 1 DAY")->fetch_assoc()['count'];
              $totalBookings = $conn->query("SELECT COUNT(*) AS count FROM Booking")->fetch_assoc()['count'];
              $totalCustomers = $conn->query("SELECT COUNT(*) AS count FROM Customer")->fetch_assoc()['count'];
              $totalReviews = $conn->query("SELECT COUNT(*) AS count FROM Feedback")->fetch_assoc()['count'];
              $totalHotels = $conn->query("SELECT COUNT(*) AS count FROM Hotel")->fetch_assoc()['count'];
              $avgRooms = $conn->query("SELECT ROUND(AVG(room_count),0) AS avgcount FROM (SELECT COUNT(ROOM_NO) AS room_count FROM Hotel_Room GROUP BY HL_ID) AS HR;")->fetch_assoc()['avgcount'];
              $totalSignin = $conn->query("SELECT COUNT(*) AS count FROM SIGNIN")->fetch_assoc()['count'];
              ?>
              <div class="row mb-4 text-center" style="display:flex;align-items:center;justify-content:space-evenly;">
                  <div class="col-md-3">
                      <div class="card p-3">
                          <h5>Recent Bookings</h5>
                          <p class="display-6 text-success"><?php echo $recentBookings; ?></p>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card p-3">
                          <h5>Total Sign-ins</h5>
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
              <div class="row mb-4 text-center" style="display:flex;align-items:center;justify-content:space-evenly;">
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
              <div class="row mb-4 text-center" style="display:flex;align-items:center;justify-content:space-evenly;">
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
  </body>
</html>