<?php
session_start();
if (!isset($_SESSION["filteredData"]) || $_SESSION["filteredData"] != "true") {
    header("location:hotelList_unfiltered.php");
    exit();
}

// Fetch user preferences
$city = $_GET['city'];
$roomID = $_GET['roomID'];
$price = $_GET['price'];

// Include database connection
include 'partials/connectDB.php';

try {
    // Prepare SQL query with the updated schema
    $sql = "SELECT 
                H.H_NAME,
                L.L_NAME AS City,
                L.STATE,
                R.R_NAME,
                HR.PRICE,
                HR.ROOM_NO,
                HR.HL_ID,
                H.H_IMG,
                (SELECT AVG(RATING) FROM FEEDBACK WHERE HR.HL_ID=HL_ID GROUP BY HL_ID) AS 'RATING'
            FROM Hotel H
            JOIN Location L ON H.LOCATION_ID = L.L_ID
            JOIN Hotel_Room HR ON H.HL_ID = HR.HL_ID
            JOIN Rooms R ON HR.R_ID = R.R_ID
            WHERE L.L_NAME = ? 
              AND R.R_ID = ? 
              AND HR.PRICE <= ? 
              AND HR.STATUS = 'Available'";
    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $city, $roomID, $price);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Close statement and connection
    $stmt->close();
    $conn->close();
    } 
    catch (Exception $e) {
        echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Results</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      background-color: #f0f4f8;
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
    .hotel-cards {
      display: flex;
      flex-wrap: wrap; /* Allow cards to wrap to the next line on smaller screens */
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }
    .hotel-card {
      display: flex; /* Aligns the image and details side by side */
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 16px;
      max-width: 1200px; /* Increased width of the card */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      align-items: center; /* Vertically center the content */
      width: 100%; /* Allow it to stretch to full width */
    }
    .hotel-card img {
      max-width: 400px; /* Image width */
      height: auto;
      border-radius: 8px;
      margin-right: 20px; /* Adds space between image and details */
    }
    .hotel-info {
      width:1000px;
    }
    .hotel-info h2 {
      margin: 0;
      font-size: 1.8em; /* Adjusted font size */
      color: #333;
    }
    .hotel-info p {
      margin: 10px 0;
      color: #555;
    }
    .hotel-card .btn {
      display: inline-block;
      margin-top: 12px;
      padding: 6px 12px; /* Made the button smaller */
      background-color: #004080;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      text-align: center;
    }
    .hotel-card .btn:hover {
      background-color: #0056b3;
    }
    .hotel-card .btn-container {
      display: flex;
      flex-direction:column;
      justify-content: center;
      width: 50%;
      margin-right: 20px;
    }
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #004080;
        padding: 15px 50px;
        color: white;
    }

    .navbar .logo {
        font-size: 24px;
        font-weight: 600;
    }

    .nav-links {
        display: flex;
        list-style: none;
    }

    .nav-links li {
        margin: 0 15px;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: 400;
    }
    body{
      background-color:#B0C4DE;
    }
  </style>
</head>
<body>
<nav class="navbar">
        <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES</div>
        <ul class="nav-links">
        <li><a href="hoteldesc.php">Hotels</a></li>
        <li><a href="contact.php">View Offers</a></li>
        </ul>
    </nav>
  <!-- <header>
    <h2>SES Hotel</h2>
    <nav>
      <a href="hoteldesc.php">Hotels</a>
      <a href="contact.php">View Offers</a>
    </nav>
  </header> -->
  <?php 
    // Check for results
    if ($result->num_rows > 0) {
      echo '<div class="hotel-cards">';
      while ($row = $result->fetch_assoc()) {
        // Generate star rating
        $rating = intval($row['RATING']);
        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
    
        // Display hotel card with buttons
        echo '<div class="hotel-card">
                <img src="'.htmlspecialchars($row['H_IMG']).'" alt="Room Image">
                <div class="hotel-info">
                  <h2>' . htmlspecialchars($row['H_NAME']) . '</h2>
                  <p>Location: ' . htmlspecialchars($row['City']) . ', ' . htmlspecialchars($row['STATE']) . '</p>
                  <p>Room: ' . htmlspecialchars($row['R_NAME']) .'</p>
                  <p>Price: $' . number_format($row['PRICE'], 2) . ' per night</p>
                  <p>Rating: ' . $stars . '</p>
                </div>
                <div class="btn-container">
                  <a href="roomDetails.php?hid=' . urlencode($row['HL_ID']) . '&room_no=' . urlencode($row['ROOM_NO']) . '" class="btn">View Details</a>
                <a href="hotelBooking.php?hid=' . urlencode($row['HL_ID']) . '&room_no=' . urlencode($row['ROOM_NO']) . '" class="btn">Book Now</a>

                </div>
              </div>';
    }
    
      echo '</div>';
  }
   else {
        echo '<h3>No Hotel Available.</h3>';
    }
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
