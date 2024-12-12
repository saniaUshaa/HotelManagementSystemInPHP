<?php
require "partials/connectDB.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === "add") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['loc'];
        $mang = $_POST['manager'];
        $sql = "INSERT INTO Hotel (H_NAME, PHONENO, EMAIL, LOCATION_ID, MANAGER) VALUES ('$name', '$phone', '$email', '$rooms', '$address','$mang')";
        
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }
    } elseif ($action === "remove") {
        $HID = $_POST['hotelID'];
        $sql = "DELETE FROM Hotel WHERE HL_ID = '$HID'";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }
    } elseif ($action === "update") {
        $HID = $_POST['hotelID'];
        $field = $_POST['updateField'];
        $newValue = $_POST['newValue'];
        $sql = "UPDATE Hotel SET $field = '$newValue' WHERE HL_ID = '$HID'";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }
    }
}


$hotels = [];
$sql = "SELECT * FROM Hotel";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .hidden {
        display: none;
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
            <div class="text-center">
                <div class="container my-3 d-flex justify-content-end">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithOptions" aria-controls="offcanvasWithOptions">Manage Hotels</button>
                </div>
                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithOptions" aria-labelledby="offcanvasWithOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithOptionsLabel">Manage Hotels</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <button class="btn btn-outline-secondary mb-3" onclick="goBack()">
                            <i class="lni lni-arrow-left"></i> Back
                        </button>

                        <!-- Action Selection -->
                        <div id="actionSelect" class="text-center">
                            <h5>Select Action</h5><br>
                            <button class="btn btn-success my-2" onclick="showForm('add')">Add Hotel</button><br>
                            <button class="btn btn-warning my-2" onclick="showForm('remove')">Remove Hotel</button><br>
                            <button class="btn btn-info my-2" onclick="showForm('update')">Update Hotel</button>
                        </div>
                        <!-- Add Hotel Form -->
                        <div id="addForm" class="hidden">
                            <h5>Add Hotel</h5>
                            <form class="row g-3" action="manageHotel.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <!-- Form Fields -->
                                <div class="col-12">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Hotel Name">
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                                <div class="col-12">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+92">
                                </div>
                                <div class="col-12">
                                    <label for="loc" class="form-label">Location ID</label>
                                    <input type="number" class="form-control" id="loc" name="loc">
                                </div>
                                <div class="col-12">
                                    <label for="manager" class="form-label">Hotel Manager Name</label>
                                    <input type="text" class="form-control" id="manager" name="manager">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>

                        <!-- Remove Hotel Form -->
                        <div id="removeForm" class="hidden">
                            <h5>Remove Hotel</h5>
                            <form class="row g-3" action="manageHotel.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <div class="col-12">
                                    <label for="hotelID" class="form-label">Hotel ID</label>
                                    <input type="text" class="form-control" id="hotelID" name="hotelID" placeholder="Hotel ID">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </div>
                            </form>
                        </div>

                        <!-- Update Hotel Form -->
                        <div id="updateForm" class="hidden">
                            <h5>Update Hotel Info</h5>
                            <form class="row g-3" action="manageHotel.php" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="col-12">
                                    <label for="hid" class="form-label">Hotel ID</label>
                                    <input type="text" class="form-control" id="hid" name="hotelID" placeholder="Hotel ID">
                                </div>
                                <div class="col-12">
                                    <label for="updateField" class="form-label">Field to Update</label>
                                    <select class="form-select" id="updateField" name="updateField">
                                        <option value="H_NAME">Name</option>
                                        <option value="PHONENO">Phone</option>
                                        <option value="EMAIL">Email</option>
                                        <option value="DESCRIPTION">Description</option>
                                        <option value="MANAGER">Manager Name</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="newValue" class="form-label">New Value</label>
                                    <input type="text" class="form-control" id="newValue" name="newValue" placeholder="New Value">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Hotel List -->
                <div class="container my-5">
                    <h5>Hotel Records</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hotel ID</th>
                                <th>Hotel Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location ID</th>
                                <th>Manager Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hotels as $hotel): ?>
                                <tr>
                                <td><?= $hotel['HL_ID'] ?></td>
                                <td><?= $hotel['H_NAME'] ?></td>
                                <td><?= $hotel['EMAIL'] ?></td>
                                <td><?= $hotel['PHONENO'] ?></td>
                                <td><?= $hotel['LOCATION_ID'] ?></td>
                                <td><?= $hotel['MANAGER'] ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
  </div>           

    <script>
        function showForm(action) {
            document.getElementById('actionSelect').classList.add('hidden');
            document.getElementById('addForm').classList.add('hidden');
            document.getElementById('removeForm').classList.add('hidden');
            document.getElementById('updateForm').classList.add('hidden');

            if (action === 'add') {
                document.getElementById('addForm').classList.remove('hidden');
            } else if (action === 'remove') {
                document.getElementById('removeForm').classList.remove('hidden');
            } else if (action === 'update') {
                document.getElementById('updateForm').classList.remove('hidden');
            }
        }
    </script>
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
    <script>
        function goBack() {
            document.getElementById('actionSelect').classList.remove('hidden');
            document.getElementById('addForm').classList.add('hidden');
            document.getElementById('removeForm').classList.add('hidden');
            document.getElementById('updateForm').classList.add('hidden');
        }
    </script>
</body>
</html>
