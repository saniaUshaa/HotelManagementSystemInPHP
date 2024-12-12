<?php
require "partials/connectDB.php";
$tables = ["Hotel", "SIGNIN", "Admin", "Customer", "Employees", "Rooms", "Hotel_Room", "Discount", "Booking", "Feedback", "Hotel_Services"];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <?php require "partials/verticalnav_css.php"; ?>
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
        <div class="main p-3">
            <div class="container my-5">
                <?php foreach ($tables as $table): ?>
                    <?php
                    // Fetch data from the table
                    $query = "SELECT * FROM $table";
                    $result = $conn->query($query);
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center"><?php echo htmlspecialchars($table); ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <div class="table-container">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <?php foreach ($result->fetch_fields() as $field): ?>
                                                    <th><?php echo htmlspecialchars($field->name); ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <?php foreach ($row as $value): ?>
                                                        <td><?php echo htmlspecialchars($value); ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-center">No data available for this table.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php $conn->close(); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
