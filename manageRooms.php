<?php
require "partials/connectDB.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Add Room
    if ($action === "add") {
        $status = $_POST['status'];
        $hlId = $_POST['hlId'];
        $rId = $_POST['rId'];
        $price = $_POST['price'];
        $roomNo=$_POST['roomNo'];

        // Check if Room ID exists
        $checkRoomSql = "SELECT * FROM Rooms WHERE R_ID = '$rId'";
        $checkRoomResult = $conn->query($checkRoomSql);
        if ($checkRoomResult->num_rows <= 0) {
            $alertMessage = "Error: No such Room exists in the system!";
        } else {
            $sql = "INSERT INTO Hotel_Room (ROOM_NO, HL_ID, R_ID, PRICE, STATUS) VALUES ('$roomNo', '$hlId', '$rId', '$price', '$status')";
            if ($conn->query($sql)) {
                $alertMessage = "Room added successfully!";
            } else {
                $alertMessage = "Error: " . $conn->error;
            }
        }
    }
    // Remove Room
    elseif ($action === "remove") {
        $roomNo = $_POST['roomNo'];
        $hlId = $_POST['hlId'];

        $sql = "DELETE FROM Hotel_Room WHERE ROOM_NO = '$roomNo' AND HL_ID = '$hlId'";
        if ($conn->query($sql)) {
            $alertMessage = "Room removed successfully!";
        } else {
            $alertMessage = "Error: " . $conn->error;
        }
    }
    // Update Room
    elseif ($action === "update") {
        $roomNo = $_POST['roomNo'];
        $hlId = $_POST['hlId'];
        $field = $_POST['updateField'];
        $newValue = $_POST['newValue'];

        if ($field == 'R_ID') {
            // Check if the new Room ID exists
            $checkRoomSql = "SELECT * FROM Rooms WHERE R_ID = '$newValue'";
            $checkRoomResult = $conn->query($checkRoomSql);
            if ($checkRoomResult->num_rows <= 0) {
                $alertMessage = "Error: No such Room exists in the system!";
            } else {
                $sql = "UPDATE Hotel_Room SET $field = '$newValue' WHERE ROOM_NO = '$roomNo' AND HL_ID = '$hlId'";
                if ($conn->query($sql)) {
                    $alertMessage = "Room updated successfully!";
                } else {
                    $alertMessage = "Error: " . $conn->error;
                }
            }
        } else {
            $sql = "UPDATE Hotel_Room SET $field = '$newValue' WHERE ROOM_NO = '$roomNo' AND HL_ID = '$hlId'";
            if ($conn->query($sql)) {
                $alertMessage = "Room updated successfully!";
            } else {
                $alertMessage = "Error: " . $conn->error;
            }
        }
    }
}

// Fetch Room Data
$rooms = [];
$sql = "SELECT * FROM Hotel_Room";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Hotel Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
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
            <div class="container my-4">
                <?php if (!empty($alertMessage)): ?>
                    <div class="alert <?php echo strpos($alertMessage, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                        <?php echo $alertMessage; ?>
                    </div>
                <?php endif; ?>

                <div class="container my-3 d-flex justify-content-end">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithOptions" aria-controls="offcanvasWithOptions">Manage Hotel Rooms</button>
                </div>

                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithOptions" aria-labelledby="offcanvasWithOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithOptionsLabel">Manage Hotel Rooms</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <button class="btn btn-outline-secondary mb-3" onclick="goBack()">
                            <i class="lni lni-arrow-left"></i> Back
                        </button>

                        <!-- Action Selection -->
                        <div id="actionSelect" class="text-center">
                            <h5>Select Action</h5><br>
                            <button class="btn btn-success my-2" onclick="showForm('add')">Add Room</button><br>
                            <button class="btn btn-warning my-2" onclick="showForm('remove')">Remove Room</button><br>
                            <button class="btn btn-info my-2" onclick="showForm('update')">Update Room</button>
                        </div>

                        <!-- Add Room Form -->
                        <div id="addForm" class="hidden">
                            <h5>Add Room</h5>
                            <form class="row g-3" action="manageRooms.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <div class="col-12">
                                    <label for="roomNo" class="form-label">Room No</label>
                                    <input type="text" class="form-control" id="roomNo" name="roomNo" placeholder="Room No">
                                </div>
                                <div class="col-12">
                                    <label for="hlId" class="form-label">Hotel ID</label>
                                    <input type="text" class="form-control" id="hlId" name="hlId" placeholder="Hotel ID">
                                </div>
                                <div class="col-12">
                                    <label for="rId" class="form-label">Room ID</label>
                                    <input type="text" class="form-control" id="rId" name="rId" placeholder="Room Type ID">
                                </div>
                                <div class="col-12">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" placeholder="Room Price">
                                </div>
                                <div class="col-12">
                                    <label for="status" class="form-label">Room Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="Available">Available</option>
                                        <option value="Occupied">Occupied</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>

                        <!-- Remove Room Form -->
                        <div id="removeForm" class="hidden">
                            <h5>Remove Room</h5>
                            <form class="row g-3" action="manageRooms.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <div class="col-12">
                                    <label for="roomNo" class="form-label">Room Number</label>
                                    <input type="text" class="form-control" id="roomNo" name="roomNo" placeholder="Room Number">
                                </div>
                                <div class="col-12">
                                    <label for="hlId" class="form-label">Hotel ID</label>
                                    <input type="text" class="form-control" id="hlId" name="hlId" placeholder="Hotel ID">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </div>
                            </form>
                        </div>

                        <!-- Update Room Form -->
                        <div id="updateForm" class="hidden">
                            <h5>Update Room</h5>
                            <form class="row g-3" action="manageRooms.php" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="col-12">
                                    <label for="roomNo" class="form-label">Room Number</label>
                                    <input type="text" class="form-control" id="roomNo" name="roomNo" placeholder="Room Number">
                                </div>
                                <div class="col-12">
                                    <label for="hlId" class="form-label">Hotel ID</label>
                                    <input type="text" class="form-control" id="hlId" name="hlId" placeholder="Hotel ID">
                                </div>
                                <div class="col-12">
                                    <label for="updateField" class="form-label">Field to Update</label>
                                    <select class="form-select" id="updateField" name="updateField">
                                        <option value="PRICE">Price</option>
                                        <option value="STATUS">Room Status</option>
                                        <option value="R_ID">Room ID</option>
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

                <!-- Room List -->
                <div class="container my-5">
                    <h5>Hotel Room Records</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Room No</th>
                                <th>Hotel ID</th>
                                <th>Room ID</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?php echo $room['ROOM_NO']; ?></td>
                                    <td><?php echo $room['HL_ID']; ?></td>
                                    <td><?php echo $room['R_ID']; ?></td>
                                    <td><?php echo $room['PRICE']; ?></td>
                                    <td><?php echo $room['STATUS']; ?></td>
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
            // Hide all forms
            document.getElementById('addForm').classList.add('hidden');
            document.getElementById('removeForm').classList.add('hidden');
            document.getElementById('updateForm').classList.add('hidden');
            document.getElementById('actionSelect').classList.add('hidden');

            // Show selected form
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
</body>
</html>
