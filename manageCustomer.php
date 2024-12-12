<?php
require "partials/connectDB.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) 
{
    $action = $_POST['action'];
    if ($action === "add") {
        $cnic = $_POST['cnic'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        // Check for duplicate CNIC
        $checkSql = "SELECT * FROM customer WHERE CNICNO = '$cnic'";
        $checkResult = $conn->query($checkSql);
        if ($checkResult->num_rows > 0) {
            $alertMessage = "Error: A customer with the provided CNIC already exists!";
        } 
        else {
            $sql = "INSERT INTO customer (CNICNO, C_FNAME, C_LNAME, C_EMAIL, C_PHONENO) VALUES ('$cnic', '$fname', '$lname', '$email', '$phone')";
            if ($conn->query($sql)) {
                $alertMessage = "Customer added successfully!";
            } 
            else {
                $alertMessage = "Error: " . $conn->error;
            }  
        }
    }
    //remove 
    elseif ($action === "remove") {
        $cnic = $_POST['cnic'];
        $sql = "DELETE FROM customer WHERE CNICNO = '$cnic'";
        if (!$conn->query($sql)) {
            $alertMessage = "Error: " . $conn->error;
        }
    } 
    //update
    elseif ($action === "update") {
        $cnic = $_POST['cnic'];
        $field = $_POST['updateField'];
        $newValue = $_POST['newValue'];
        $sql = "UPDATE customer SET $field = '$newValue' WHERE CNICNO = '$cnic'";
        if (!$conn->query($sql)) {
            $alertMessage = "Error: " . $conn->error;
        }
    }
}

//fetch customer data
$customers = [];
$sql = "SELECT * FROM CUSTOMER";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
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
                <!-- error msg -->
                <div class="container my-4">
                    <?php if (!empty($alertMessage)): ?>
                        <div class="alert <?php echo strpos($alertMessage, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                            <?php echo $alertMessage; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="container my-3 d-flex justify-content-end">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithOptions" aria-controls="offcanvasWithOptions">Manage Customer</button>
                </div>
                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithOptions" aria-labelledby="offcanvasWithOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithOptionsLabel">Manage Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <button class="btn btn-outline-secondary mb-3" onclick="goBack()">
                            <i class="lni lni-arrow-left"></i> Back
                        </button>

                        <!-- Action Selection -->
                        <div id="actionSelect" class="text-center">
                            <h5>Select Action</h5><br>
                            <button class="btn btn-success my-2" onclick="showForm('add')">Add Customer</button><br>
                            <button class="btn btn-warning my-2" onclick="showForm('remove')">Remove Customer</button><br>
                            <button class="btn btn-info my-2" onclick="showForm('update')">Update Customer</button>
                        </div>
                        <!-- Add Hotel Form -->
                        <div id="addForm" class="hidden">
                            <h5>Add Customer</h5>
                            <form class="row g-3" action="manageCustomer.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <div class="col-12">
                                    <label for="cnic" class="form-label">CNIC</label>
                                    <input type="text" class="form-control" id="cnic" name="cnic" placeholder="CNIC">
                                </div>
                                <div class="col-6">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name">
                                </div>
                                <div class="col-6">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name">
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                                <div class="col-12">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>

                        <!-- Remove Customer Form -->
                        <div id="removeForm" class="hidden">
                            <h5>Remove Customer</h5>
                            <form class="row g-3" action="manageCustomer.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <div class="col-12">
                                    <label for="cnic" class="form-label">CNIC</label>
                                    <input type="text" class="form-control" id="cnic" name="cnic" placeholder="CNIC">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </div>
                            </form>
                        </div>

                        <!-- Update Customer Form -->
                        <div id="updateForm" class="hidden">
                            <h5>Update Customer</h5>
                            <form class="row g-3" action="manageCustomer.php" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="col-12">
                                <label for="cnic" class="form-label">CNIC</label>
                                <input type="text" class="form-control" id="cnic" name="cnic" placeholder="00000-000000000-0">
                                </div>
                                <div class="col-12">
                                    <label for="updateField" class="form-label">Field to Update</label>
                                    <select class="form-select" id="updateField" name="updateField">
                                        <option value="C_FNAME">First Name</option>
                                        <option value="C_LNAME">Last Name</option>
                                        <option value="C_EMAIL">Email</option>
                                        <option value="C_PHONENO">Phone Number</option>
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

                <!-- Customer List -->
                <div class="container my-5">
                    <h5>Customer Records</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>CNIC</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td><?= $customer['CNICNO'] ?></td>
                                    <td><?= $customer['C_FNAME'] ?></td>
                                    <td><?= $customer['C_LNAME'] ?></td>
                                    <td><?= $customer['C_EMAIL'] ?></td>
                                    <td><?= $customer['C_PHONENO'] ?></td>
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
