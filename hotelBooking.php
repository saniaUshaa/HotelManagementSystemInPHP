<?php
// Include database connection
include 'partials/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $cnic = $_POST['cnic'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $dcode = $_POST['discount'];
    $hotelId = $_POST['hid'];
    $roomNo = $_POST['room_no'];
    $dateTime = date('Y-m-d H:i:s');
    // First, check if the customer already exists based on CNIC
    $sql = "SELECT * FROM Customer WHERE CNICNO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cnic);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows ==0) {
        // Customer doesn't exist, insert into Customer table
        $stmt->close();
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        try {
            // Insert into Customer table
            $sqlInsert = "INSERT INTO Customer (CNICNO, C_FNAME, C_LNAME, C_EMAIL, C_PHONENO) 
                          VALUES ('$cnic', '$fname', '$lname', '$email', '$phone')";
            if (mysqli_query($conn, $sqlInsert)) {
                // Get the customer ID after insert (if needed)
                $customerId = mysqli_insert_id($conn); // Get the last inserted customer ID

            }
            else {
              echo "<script>alert('Error while saving customer information. Please try again.');</script>";
            }
          } 
        catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    if ($dcode!='NULL') {
      $sqlDiscount = "SELECT * FROM Discount WHERE D_CODE = ?";
      $stmtDiscount = $conn->prepare($sqlDiscount);
      $stmtDiscount->bind_param("s", $dcode);
      $stmtDiscount->execute();
      $resultDiscount = $stmtDiscount->get_result();

      if ($resultDiscount->num_rows == 0) {
          $dcode='NULL';
      }
      $stmtDiscount->close();
    }
    // Insert into Booking table
    $sqlInsertBooking = "INSERT INTO Booking (CHECK_IN, CHECK_OUT, HL_ID, D_CODE, CNICNO, ROOM_NO,B_TIME) 
                        VALUES (?, ?, ?, ?, ?, ?,?)";
    $stmtBooking = $conn->prepare($sqlInsertBooking);
    $stmtBooking->bind_param("ssissis", $checkin, $checkout, $hotelId, $dcode, $cnic, $roomNo,$dateTime);

    if ($stmtBooking->execute()) {
        $bookingId = $stmtBooking->insert_id; // Get the inserted booking ID
        // Redirect to payment page with the booking ID
        header("Location: payment.php?booking_id=" . urlencode($bookingId));
        exit();
    } 
    else {
        echo "<script>alert('Error while saving booking information. Please try again.');</script>";
    }
    $stmtBooking->close();
}
else {
    // Get the hotel ID and room number from the query string
    if (isset($_GET['hid'], $_GET['room_no']) && is_numeric($_GET['hid']) && is_numeric($_GET['room_no'])) {
        $hotelId = intval($_GET['hid']);
        $roomNo = intval($_GET['room_no']);

        try {
            // Query to fetch room details
            $sqlRoom = "SELECT 
                            H.H_NAME, R.R_NAME, HR.PRICE, H.H_IMG
                        FROM Hotel_Room HR
                        JOIN Hotel H ON H.HL_ID = HR.HL_ID
                        JOIN Rooms R ON HR.R_ID = R.R_ID
                        WHERE H.HL_ID = ? AND HR.ROOM_NO = ?";
            $stmtRoom = $conn->prepare($sqlRoom);
            $stmtRoom->bind_param("ii", $hotelId, $roomNo);
            $stmtRoom->execute();
            $roomDetails = $stmtRoom->get_result()->fetch_assoc();

            $stmtRoom->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Invalid room or hotel ID.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
     body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
            header {
          background-color: #003366;
          color: white;
          padding: 1rem;
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
        header a {
          color: white;
          text-decoration: none;
          margin: 0 15px;
        }
        .container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            padding: 20px;
        }
        .room-image {
            flex: 1 1 50%;
            max-width: 50%;
        }
        .room-image img {
            width: 100%;
            border-radius: 8px;
        }
        .book-details {
            flex: 1 1 50%;
            max-width: 50%;
        }
        .book-details h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .book-details p {
            margin: 5px 0;
            color: #555;
        }
        .btn-pay {
            margin-top: 20px;
            background-color: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            width:100%;
            height:auto;

        }
        .btn-pay:hover {
            background-color: #0056b3;
        }
      .check {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #004080;
        padding: 15px 50px;
        color: white;
    }

    .logo {
        font-size: 26px;
        font-weight: 600;
    }
    body{
        background-color:#B0C4DE;
        }
  </style>
</head>
<body>
    <header class="check">
    <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES</div>
    <span class="navbar-text" style="font-family:italic">
        Excited to Serve You!
      </span>
    </header>
    <div style="margin:15px 0 0 15px;padding:5px;font-weight:bold;">
        <h2>Confirm Booking</h2>
    </div>
    <div class="container">
        <div class="room-image">
          <img src="<?php echo htmlspecialchars($roomDetails['H_IMG']); ?>" alt="Room Image">
          <p style="font-size:15px;margin-top:10px; margin-bottom:2px;font-weight:bold;"><?php echo htmlspecialchars($roomDetails['R_NAME']); ?></p>
          <p style="font-size:12px;">Rs.<?php echo htmlspecialchars($roomDetails['PRICE']); ?> per night</p>
        </div>
        <div class="book-details" style="font-size:15px;">
          <p style="font-size:18px;font-weight:bold;color:black;margin-top:0px;">Booking Details</p>
          <form class="row g-3"  action="/DBProject/hotelBooking.php" method="post">
          <input type="hidden" name="hid" value="<?php echo htmlspecialchars($hotelId); ?>">
          <input type="hidden" name="room_no" value="<?php echo htmlspecialchars($roomNo); ?>">
            <div class="col-12">
              <label for="cnic" class="form-label">CNIC</label>
              <input type="text" class="form-control" id="cnic" name="cnic" placeholder="00000-0000000-0" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required>
            </div>
            <div class="col-md-6">
              <label for="fname" class="form-label" >First Name</label>
              <input type="text" class="form-control" id="fname" name="fname" placeholder="Jhon" pattern="[A-Za-z]+" title="Only letters are allowed" required>
            </div>
            <div class="col-md-6">
              <label for="lname" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="lname" name="lname" placeholder="Smith" pattern="[A-Za-z]+" title="Only letters are allowed" required>
            </div>
            <div class="col-12">
              <label for="phone" class="form-label">Phone No.</label>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="1234-5678900" pattern="[0-9]{4}-[0-9]{7}" required>
            </div>
            <div class="col-12">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-md-6">
              <label for="checkin" class="form-label">Check-in</label>
              <input type="date" class="form-control" id="checkin" name="checkin" required>
            </div>
            <div class="col-md-6">
              <label for="checkout" class="form-label">Check-out</label>
              <input type="date" class="form-control" id="checkout" name="checkout" required>
            </div>
            <div class="col-6">
              <label for="discount" class="form-label">Discount Code</label>
              <input type="text" class="form-control" id="discount" name="discount" pattern="[A-Z]*" value="NULL">
            </div>
            <div class="col-12" style="display:flex;justify-content:center;align-items:center;">
              <button type="submit" class="btn-pay">Pay Now</button>
            </div>
          </form>
        </div>
        

    </div>
    
    <?php require 'partials/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
