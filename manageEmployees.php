<?php
require "partials/connectDB.php";

$status = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === "add") {
        $name = $_POST['Name'];
        $post = $_POST['Post'];
        $hiredate = $_POST['hiredate'];
        $phone = $_POST['phone'];
        $hid = $_POST['hid'];
        $sql = "INSERT INTO Employees (E_NAME, E_POST, HIRE_DATE, PHONENO, HL_ID) VALUES ('$name', '$post', '$hiredate', '$phone', '$hid')";
        
        if ($conn->query($sql)) {
            $status = "add_success";
        } else {
            $status = "add_error";
        }
    } elseif ($action === "remove") {
        $employeeID = $_POST['EmployeeID'];
        $sql = "DELETE FROM Employees WHERE E_ID = '$employeeID'";
        
        if ($conn->query($sql)) {
            $status = "remove_success";
        } else {
            $status = "remove_error";
        }
    } elseif ($action === "update") {
        $employeeID = $_POST['EmployeeID'];
        $field = $_POST['UpdateField'];
        $newValue = $_POST['NewValue'];
        $sql = "UPDATE Employees SET $field = '$newValue' WHERE E_ID = '$employeeID'";
        
        if ($conn->query($sql)) {
            $status = "update_success";
        } else {
            $status = "update_error";
        }
    }
}

// Fetch Employees for Display
$employees = [];
$sql = "SELECT * FROM Employees";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
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
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithOptions" aria-controls="offcanvasWithOptions">Manage Employees</button>
                </div>
                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithOptions" aria-labelledby="offcanvasWithOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithOptionsLabel">Manage Employees</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <button class="btn btn-outline-secondary mb-3" onclick="goBack()">
                            <i class="lni lni-arrow-left"></i> Back
                        </button>

                        <!-- Action Selection -->
                        <div id="actionSelect" class="text-center">
                            <h5>Select Action</h5><br>
                            <button class="btn btn-success my-2" onclick="showForm('add')">Add Employee</button><br>
                            <button class="btn btn-warning my-2" onclick="showForm('remove')">Remove Employee</button><br>
                            <button class="btn btn-info my-2" onclick="showForm('update')">Update Employee</button>
                        </div>

                        <!-- Add Employee Form -->
                        <div id="addForm" class="hidden">
                            <h5>Add Employee</h5>
                            <form class="row g-3" action="manageEmployees.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <!-- Form Fields -->
                                <div class="col-12">
                                    <label for="Name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="Name" name="Name" placeholder="Full Name">
                                </div>
                                <div class="col-12">
                                    <label for="Post" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="post" name="Post" placeholder="Position">
                                </div>
                                <div class="col-12">
                                    <label for="hiredate" class="form-label">Hire Date</label>
                                    <input type="date" class="form-control" id="hiredate" name="hiredate" placeholder="DD-MM-YYYY">
                                </div>
                                <div class="col-12">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+92">
                                </div>
                                <div class="col-12">
                                    <label for="hid" class="form-label">Hotel ID</label>
                                    <input type="text" class="form-control" id="hid" name="hid">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>

                        <!-- Remove Employee Form -->
                        <div id="removeForm" class="hidden">
                            <h5>Remove Employee</h5>
                            <form class="row g-3" action="manageEmployees.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <div class="col-12">
                                    <label for="empID" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="empID" name="EmployeeID" placeholder="Employee ID">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </div>
                            </form>
                        </div>

                        <!-- Update Employee Form -->
                        <div id="updateForm" class="hidden">
                            <h5>Update Employee</h5>
                            <form class="row g-3" action="manageEmployees.php" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="col-12">
                                    <label for="empIDUpdate" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="empIDUpdate" name="EmployeeID" placeholder="Employee ID">
                                </div>
                                <div class="col-12">
                                    <label for="updateField" class="form-label">Field to Update</label>
                                    <select class="form-select" id="updateField" name="UpdateField">
                                        <option value="E_NAME">Name</option>
                                        <option value="E_POST">Position</option>
                                        <option value="PHONENO">Phone Number</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="newValue" class="form-label">New Value</label>
                                    <input type="text" class="form-control" id="newValue" name="NewValue" placeholder="New Value">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Employee List -->
                <div class="container my-5">
                    <h5>Employee Records</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Hire Date</th>
                                <th>Phone</th>
                                <th>Hotel ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?= $employee['E_ID'] ?></td>
                                    <td><?= $employee['E_NAME'] ?></td>
                                    <td><?= $employee['E_POST'] ?></td>
                                    <td><?= $employee['HIRE_DATE'] ?></td>
                                    <td><?= $employee['PHONENO'] ?></td>
                                    <td><?= $employee['HL_ID'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
  </div>           

    <script>
        // Show specific forms based on action
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
