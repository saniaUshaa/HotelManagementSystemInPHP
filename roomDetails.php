<?php
// Include database connection
include 'partials/connectDB.php';

// Get the hotel ID and room ID from the query string
if (isset($_GET['hid'], $_GET['room_no']) && is_numeric($_GET['hid']) && is_numeric($_GET['room_no'])) {
    $hotelId = intval($_GET['hid']);
    $roomNo = intval($_GET['room_no']);

    try {
        // Query to fetch room details
        $sqlRoom = "SELECT 
                        H.H_NAME ,HR.HL_ID,HR.ROOM_NO,R.R_NAME, HR.PRICE, H.H_IMG,
                        (SELECT AVG(RATING) FROM FEEDBACK WHERE HR.HL_ID = H.HL_ID) AS 'RATING',
                        R.R_DESCRIPTION
                    FROM Hotel_Room HR
                    JOIN Hotel H ON H.HL_ID = HR.HL_ID
                    JOIN Rooms R ON HR.R_ID = R.R_ID
                    WHERE H.HL_ID = ? AND HR.ROOM_NO = ?";
        $stmtRoom = $conn->prepare($sqlRoom);
        $stmtRoom->bind_param("ii", $hotelId, $roomNo);
        $stmtRoom->execute();
        $roomDetails = $stmtRoom->get_result()->fetch_assoc();

        // Query to fetch reviews
        $sqlReviews = "SELECT (SELECT C_EMAIL FROM CUSTOMER WHERE CNICNO=F.CNICNO) AS EMAIL, F.RATING, F.REVIEW 
                       FROM FEEDBACK F 
                       WHERE F.HL_ID = ? LIMIT 4";
        $stmtReviews = $conn->prepare($sqlReviews);
        $stmtReviews->bind_param("i", $hotelId);
        $stmtReviews->execute();
        $reviews = $stmtReviews->get_result();

        // Query to fetch services
        $sqlServices = "SELECT S.S_NAME
                       FROM HOTEL_SERVICES HS
                       JOIN SERVICES S ON S.S_ID=HS.S_ID
                       WHERE HS.HL_ID = ? LIMIT 4";
        $stmtServices = $conn->prepare($sqlServices);
        $stmtServices->bind_param("i", $hotelId);
        $stmtServices->execute();
        $services = $stmtServices->get_result();

        $stmtRoom->close();
        $stmtReviews->close();
        $stmtServices->close();   
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid room or hotel ID.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          
        }
        .room-top {
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
        .room-details {
            flex: 1 1 50%;
            max-width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }
        .room-details h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .room-details p {
            margin: 5px 0;
            color: #555;
        }
        .stars {
            color: #ffc107;
        }
        .btn-book {
            margin-top: 20px;
           background-color: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
        }
        .btn-book:hover {
            background-color: #0056b3;
        }
        .description, .reviews {
            padding: 20px;
            margin-top: 20px;
            border-top: 1px solid #ddd;
        }
        .description p{
            font-size:18px;
        }
        .review-item {
            margin-bottom: 15px;
        }
        .review-item h5 {
            margin: 0;
            color: #333;
        }
        .review-item .rating {
            color: #ffc107;
        }
        
    </style>
</head>
<body class="details">
    <?php require 'partials/hotel_nav.php'?>
    <div style="margin:15px 0 0 15px;padding:5px;font-weight:bold;">
        <h2><?php echo htmlspecialchars($roomDetails['R_NAME']); ?> at <?php echo htmlspecialchars($roomDetails['H_NAME']); ?></h2>
    </div>
    <div class="room-top">
        <div class="room-image">
        <img src="<?php echo htmlspecialchars($roomDetails['H_IMG']); ?>" alt="Room Image">
        </div>
        <div class="room-details" style="font-size:25px;">
            <h3><strong>Rs.<?php echo number_format($roomDetails['PRICE'], 2); ?> per night</strong></h3>
            <p><span class="stars"><?php echo str_repeat('★', intval($roomDetails['RATING'])) . str_repeat('☆', 5 - intval($roomDetails['RATING'])); ?></span></p>            
            <p><strong>Features:</strong></p>
            <div style="display:flex;justify-content:space-around;">
                <div style="font-size:15px;border-radius:10px;padding:5px;background-color:#FFFFF0;">
                    <p>balcony</p>
                </div>
                <div style="font-size:15px;border-radius:10px;padding:5px;background-color:#FFFFF0;">
                    <p>bedroom</p>
                </div>
                <div style="font-size:15px;border-radius:10px;padding:5px;background-color:#FFFFF0;">
                    <p>safe</p>
                </div>
            </div>
            <p><strong>Services:</strong></p>
            <div style="display:flex;justify-content:space-around;">
                <?php if ($services->num_rows > 0): ?>
                    <?php while ($service = $services->fetch_assoc()): ?>
                        <div style="font-size:15px;border-radius:10px;padding:5px;background-color:#FFFFF0;">
                            <p><?php echo htmlspecialchars($service['S_NAME']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No services available.</p>
                <?php endif; ?>
            </div>
            <a href="hotelBooking.php?hid=<?php echo urlencode($roomDetails['HL_ID']); ?>&room_no=<?php echo urlencode($roomDetails['ROOM_NO']); ?>" class="btn-book">Book Now</a>
            </div>
    </div>
    <div class="description">
        <h3>Room Description</h3>
        <p><?php echo htmlspecialchars($roomDetails['R_DESCRIPTION']); ?></p>
    </div>
    <div class="reviews">
        <h3 style="margin-bottom:20px;">Customer Reviews</h3>
        <?php if ($reviews->num_rows > 0): ?>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-item">
                    <h5><?php echo htmlspecialchars($review['EMAIL']); ?></h5>
                    <p class="rating"><?php echo str_repeat('★', intval($review['RATING'])) . str_repeat('☆', 5 - intval($review['RATING'])); ?></p>
                    <p><?php echo htmlspecialchars($review['REVIEW']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews available for this room.</p>
        <?php endif; ?>
    </div>
    <?php require 'partials/footer.php' ?>
</body>
</html>
