<?php require 'partials/connectDB.php';

if (isset($_GET['booking_id']) && is_numeric($_GET['booking_id'])) {
    $bookingId = intval($_GET['booking_id']);
    try {
        // Query to fetch Booking details
        $sqlBooking = "SELECT 
                        H.H_NAME,HR.ROOM_NO,R.R_NAME,P.AMOUNT,
                        B.CHECK_OUT,B.CHECK_IN,P.P_DATE,C.C_EMAIL,C.CNICNO
                    FROM Hotel_Room HR
                    JOIN Hotel H ON H.HL_ID=HR.HL_ID
                    JOIN Rooms R ON HR.R_ID = R.R_ID
                    JOIN Booking B ON B.ROOM_NO=HR.ROOM_NO AND B.HL_ID=HR.HL_ID
                    JOIN PAYMENT P ON P.B_ID=B.B_ID 
                    JOIN Customer C ON C.CNICNO=B.CNICNO
                    WHERE B.B_ID = ?";

        $stmtBook = $conn->prepare($sqlBooking);
        $stmtBook->bind_param("i", $bookingId);
        $stmtBook->execute();
        $bookDetails = $stmtBook->get_result()->fetch_assoc();
        
        $stmtBook->close();

        if (!$bookDetails) {
            throw new Exception("Booking not found.");
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid Booking ID.";
    exit();
}

?> 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HotelBookingSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
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
        .check {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #004080;
            padding: 15px 50px;
            color: white;
        }
        .logo {
            font-size: 21px;
            font-weight: 600;
        }
        .card {
            width: 45rem;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #E6EFF7;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-img-top {
            display: block;
            margin: 0 auto 20px;
            width: 15%;
            height: 20%;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        body {
            background-color: #B0C4DE;
        }
    </style>
</head>
<body>
    <header class="check">
        <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES</div>
        <nav>
            <a href="hotelList_unfiltered.php">Continue Searching</a>
            <a href="user_account.php?cnic=<?php echo urlencode($bookDetails['CNICNO']); ?>" class="btn-book">My Account</a>
        </nav>
    </header>
  <div class="card">
    <img src="imgs/payment.jpg" class="card-img-top" alt="Payment Image">
    <div class="card-body">
        <h5 class="card-title text-center">Booking Details</h5>
        <div class="details">
            <p><strong>Hotel:</strong> <?php echo htmlspecialchars($bookDetails['H_NAME']); ?></p>
            <div style="display:flex;justify-content:space-between">
                <div>
                    <p><strong>Date</strong></p>
                </div>
                <div>
                    <p><?php echo htmlspecialchars($bookDetails['P_DATE']); ?></p>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between">
                <div>
                    <p><strong>Room No.</strong></p>
                </div>
                <div>
                    <p><?php echo htmlspecialchars($bookDetails['ROOM_NO']); ?></p>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between">
                <div>
                    <p><strong>Room Type</strong></p>
                </div>
                <div>
                    <p><?php echo htmlspecialchars($bookDetails['R_NAME']); ?></p>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between">
                <div>
                    <p><strong>Email</strong></p>
                </div>
                <div>
                    <p><?php echo htmlspecialchars($bookDetails['C_EMAIL']); ?></p>
                </div>
            </div>
        </div>
        <div style="display:flex;justify-content:space-between;border-top:solid black 1px;margin-top:15px;padding-top:10px;">
            <div>
                <p><strong>Payment Amount</strong></p>
            </div>
            <div>
                <p><strong>Rs. <?php echo htmlspecialchars($bookDetails['AMOUNT']); ?></strong></p>
            </div>
        </div>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
