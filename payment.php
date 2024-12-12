<?php
// Include database connection
include 'partials/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $cardNumber = preg_replace('/\s+/', '', $_POST['cardNumber']);
    $bookingId = $_POST['booking_id'];
    $DateTime = date('Y-m-d H:i:s');
    $disPayment=$_POST['discountedPrice'];
    //echo $disPayment;
    try {
        // Insert payment record
        $sqlPayment = "INSERT INTO Payment (CARD_NO, P_DATE, AMOUNT, B_ID) VALUES (?, ?, ?, ?)";
        $stmtPayment = $conn->prepare($sqlPayment);
        $stmtPayment->bind_param("ssdi", $cardNumber, $DateTime, $disPayment, $bookingId);
        //echo 'HELLO';
        if ($stmtPayment->execute()) {
            $stmtPayment->close();
            // Redirect to confirmation page
            header("Location: confirmation.php?booking_id=" . urlencode($bookingId));
            exit();
        } else {
            throw new Exception("Failed to process payment.");
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    // Get the booking ID from the query string
    if (isset($_GET['booking_id']) && is_numeric($_GET['booking_id'])) {
        $bookingId = intval($_GET['booking_id']);
        try {
            // Query to fetch booking details
            $sqlBooking = "SELECT 
                            H.H_NAME, R.R_NAME, HR.PRICE, B.D_CODE, 
                            DATEDIFF(CHECK_OUT, CHECK_IN) AS StayDays 
                        FROM Hotel_Room HR 
                        JOIN Hotel H ON H.HL_ID = HR.HL_ID 
                        JOIN Rooms R ON HR.R_ID = R.R_ID 
                        JOIN Booking B ON B.ROOM_NO = HR.ROOM_NO AND B.HL_ID = HR.HL_ID 
                        WHERE B.B_ID = ?";
            
            $stmtBook = $conn->prepare($sqlBooking);
            $stmtBook->bind_param("i", $bookingId);
            $stmtBook->execute();
            $bookDetails = $stmtBook->get_result()->fetch_assoc();
            $stmtBook->close();

            if (!$bookDetails) {
                throw new Exception("Booking not found.");
            }

            $dcode = $bookDetails["D_CODE"];
            $totalPayment = $bookDetails["PRICE"] * $bookDetails["StayDays"];
            $disPayment = $totalPayment;

            // Check customer count for discount code decision
            $sql = "SELECT COUNT(CNICNO) AS cust_count FROM Booking WHERE CNICNO = (SELECT CNICNO FROM Booking WHERE B_ID = ?)";
            $stmtcheck = $conn->prepare($sql);
            $stmtcheck->bind_param("i", $bookingId);
            $stmtcheck->execute();
            $checkResult = $stmtcheck->get_result()->fetch_assoc();
            $stmtcheck->close();

            if ($dcode == 'NULL') {
                if ($bookDetails['StayDays'] >= 20) {
                    $dcode = 'LONGSTAY20';
                } elseif ($checkResult['cust_count'] > 1) {
                    $dcode = 'REWARDYOU';
                } elseif ($checkResult['cust_count'] == 1) {
                    $dcode = 'WELCOME10';
                }
            }

            // Apply discount if any
            if ($dcode != 'NULL') {
                $sqlD = "SELECT D_RATE FROM DISCOUNT WHERE D_CODE = ?";
                $stmtrate = $conn->prepare($sqlD);
                $stmtrate->bind_param("s", $dcode);
                $stmtrate->execute();
                $discountRes = $stmtrate->get_result()->fetch_assoc();
                $stmtrate->close();
                $drate = $discountRes["D_RATE"];

                if ($dcode == 'REWARDYOU') {
                    $drate = $drate * (1 + ($checkResult['cust_count'] / 10));
                }
                $disc_rate = 1.0 - ($drate/100);
                $disPayment = $disPayment * $disc_rate;
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Invalid Booking ID.";
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
        main {
            flex: 1;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #003366;
            border-top: 1px solid #ddd;
            padding: 10px;
            text-align: right; /* Aligns the text to the right */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            font-size:20px;
            color:white;
        }
        .my-container p {
            margin-bottom: 5px; /* Adds consistent spacing between items */
            font-weight: 400; /* Normal weight for general text */
        }
        .my-container p strong {
            font-weight: 700; /* Bolds specific values */
        }
        .text-decoration-line-through {
            color: #dc3545; /* Adds a red color for the strikethrough */
        }
        .text-success {
            color: #28a745; /* Adds a green color for the discounted payment */
        }
    </style>
</head>
<body>
    <header>
        <i class="fa-solid fa-hotel fa-2x"></i>
        <h2>SES Hotel</h2>
    </header>
    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mb-4">Enter Card Details</h3>
                <form action="payment.php" method="post">
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($bookingId); ?>">
                    <input type="hidden" name="discountedPrice" value="<?php echo htmlspecialchars($disPayment); ?>">
    
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9123 4567" pattern="\d{4} \d{4} \d{4} \d{4}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cardName" class="form-label">Name on Card</label>
                        <input type="text" class="form-control" id="cardName" name="cardName" placeholder="John Doe" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY" pattern="\d{2}/\d{2}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" pattern="\d{3}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Make Payment</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
    <div class="my-container text-end">
        <div class="d-flex flex-column align-items-end">
            <p class="mb-1">Payment Amount: 
                <span class="text-decoration-line-through">
                    <?php echo htmlspecialchars($totalPayment); ?>
                </span>
            </p>
            <?php if ($disPayment != $totalPayment): ?>
                <p class="mb-1">Discount Applied: 
                    <strong><?php echo htmlspecialchars($discountRes["D_RATE"]); ?>%</strong>
                </p>
                <p class="mb-1">Payment After Discount: 
                    <strong class="text-success">
                        <?php echo htmlspecialchars($disPayment); ?>
                    </strong>
                </p>
            <?php else: ?>
                <p class="mb-1">Total Payment: 
                    <strong>
                        <?php echo htmlspecialchars($totalPayment); ?>
                    </strong>
                </p>
            <?php endif; ?>
        </div>
    </div>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>