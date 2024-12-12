<?php
require 'partials/connectDB.php';
if (isset($_GET['cnic']) && is_string($_GET['cnic'])) {
    $cnic = $_GET['cnic'];
    $review=false;
    $displayAlert=false;
    $alertMsg='NULL';
    if(isset($_GET['alertMsg'])){
        $alertMsg=$_GET['alertMsg'];
        $displayAlert=true;
    }
    try {
        // Query to fetch customer details
        $sqlcustomer = "SELECT 
                        CNICNO, C_EMAIL, C_PHONENO, C_FNAME, C_LNAME
                    FROM Customer 
                    WHERE CNICNO = ?";

        $stmtinfo = $conn->prepare($sqlcustomer);
        $stmtinfo->bind_param("s", $cnic);
        $stmtinfo->execute();
        $customerDetails = $stmtinfo->get_result()->fetch_assoc();
        $stmtinfo->close();

        if (!$customerDetails) {
            throw new Exception("Customer not found.");
        }

        // Query to fetch booking details
        $sqlbook = "SELECT 
                        B.B_ID, H.H_NAME, R.R_NAME, B.CHECK_IN, B.CHECK_OUT, P.AMOUNT
                    FROM Booking B 
                    JOIN Hotel H ON B.HL_ID = H.HL_ID
                    JOIN Hotel_Room HR ON HR.ROOM_NO = B.ROOM_NO AND B.HL_ID = HR.HL_ID
                    JOIN Payment P ON P.B_ID = B.B_ID
                    JOIN Rooms R ON R.R_ID = HR.R_ID
                    WHERE B.CNICNO = ?";

        $stmtbook = $conn->prepare($sqlbook);
        $stmtbook->bind_param("s", $cnic);
        $stmtbook->execute();
        $bookDetails = $stmtbook->get_result();
        $stmtbook->close();
        $bookingsAvailable = $bookDetails->num_rows > 0;

        // Query to fetch reviews
        $sqlreviews = "SELECT 
                          F.F_ID, F.REVIEW, F.RATING, H.H_NAME
                      FROM Feedback F
                      JOIN Hotel H ON F.HL_ID = H.HL_ID
                      WHERE F.CNICNO = ?";

        $stmtreviews = $conn->prepare($sqlreviews);
        $stmtreviews->bind_param("s", $cnic);
        $stmtreviews->execute();
        $reviewDetails = $stmtreviews->get_result();
        $stmtreviews->close();
        $reviewsAvailable = $reviewDetails->num_rows > 0;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid Customer ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Account - SES Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      background-color: #B0C4DE;
    }
    .card-header {
      background-color: #003366;
      color: white;
    }
    .user-info, .user-bookings, .user-reviews {
      margin-bottom: 30px;
    }
    .table {
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
      background-color: #003366;
      color: white;
      border: none;
    }
    .btn-primary:hover {
      background-color: #00509e;
    }
  </style>
</head>
<body>
<?php require 'partials/hotel_nav.php';?>
<?php if ($displayAlert) {
    if($alertMsg!='NULL'){
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'.
                $alertMsg
            .'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}    
?>
<div class="container mt-5">
    <!-- User Info Section -->
    <div class="card user-info">
      <div class="card-header">
        <h5>User Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Name: </strong><?php echo $customerDetails["C_FNAME"] . ' ' . $customerDetails["C_LNAME"] ?></p>
            <p><strong>Email: </strong><?php echo $customerDetails["C_EMAIL"] ?></p>
          </div>
          <div class="col-md-6">
            <p><strong>CNIC: </strong><?php echo $customerDetails["CNICNO"] ?></p>
            <p><strong>Phone: </strong><?php echo $customerDetails["C_PHONENO"] ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bookings Section -->
    <div class="card user-bookings">
      <div class="card-header">
        <h5>Booking History</h5>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Hotel Name</th>
              <th>Room Type</th>
              <th>Check-In</th>
              <th>Check-Out</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($bookingsAvailable): ?>
                <?php while ($booking = $bookDetails->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($booking['B_ID']); ?></td>
                  <td><?php echo htmlspecialchars($booking['H_NAME']); ?></td>
                  <td><?php echo htmlspecialchars($booking['R_NAME']); ?></td>
                  <td><?php echo htmlspecialchars($booking['CHECK_IN']); ?></td>
                  <td><?php echo htmlspecialchars($booking['CHECK_OUT']); ?></td>
                  <td>Rs. <?php echo htmlspecialchars($booking['AMOUNT']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                  <td colspan="6">No bookings found.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="card user-reviews">
      <div class="card-header">
        <h5>Reviews <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addReviewModal">Add Review</button></h5>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Review ID</th>
              <th>Hotel Name</th>
              <th>Review</th>
              <th>Rating</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($reviewsAvailable): ?>
                <?php while ($review = $reviewDetails->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($review['F_ID']); ?></td>
                  <td><?php echo htmlspecialchars($review['H_NAME']); ?></td>
                  <td><?php echo htmlspecialchars($review['REVIEW']); ?></td>
                  <td><?php echo htmlspecialchars($review['RATING']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                  <td colspan="4">No reviews found.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
</div>

<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="add_review.php" method="POST">
        <?php $review=true;?>
        <div class="modal-body">
          <div class="mb-3">
            <label for="hotelId" class="form-label">Hotel</label>
            <select class="form-select" id="hotelId" name="hotelId" required>
              <?php
              $hotels = $conn->query("SELECT HL_ID, H_NAME FROM Hotel");
              while ($hotel = $hotels->fetch_assoc()): ?>
                <option value="<?php echo $hotel['HL_ID']; ?>"><?php echo htmlspecialchars($hotel['H_NAME']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="review" class="form-label">Review</label>
            <textarea class="form-control" id="review" name="review" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1" required>
          </div>
          <input type="hidden" name="cnic" value="<?php echo $cnic; ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit Review</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
