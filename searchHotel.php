<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != "true") {
    header("location:login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/connectDB.php';

    // Sanitize input to prevent SQL injection and XSS attacks
    $city = htmlspecialchars($_POST["destination"]);
    $roomID = htmlspecialchars($_POST["room_type"]);
    $price = htmlspecialchars($_POST["price"]);

    // Build query string for redirection
    $queryString = http_build_query([
        'city' => $city,
        'roomID' => $roomID,
        'price' => $price
    ]);

    // Update session data
    session_regenerate_id();
    $_SESSION["filteredData"] = true;

    // Redirect to the hotel list page with the query string
    header("location: hotelList.php?" . $queryString);
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User - <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Guest"; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>

<body>
    <?php require "partials/hotelstyles.php"; ?>
    <nav class="navbar">
        <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES</div>
        <ul class="nav-links">
            <li><a href="hoteldesc.php">Hotels</a></li>
            <li><a href="contact.php">View Offers</a></li>
            <li><a href="hotelList_unfiltered.php" class="btn-get-started">Get Started</a></li>
        </ul>
    </nav>
    <form action="searchHotel.php" method="POST">
        <section class="booking-section">

            <div class="search-container">
                <h1 class="title">Book Your Stay</h1>
                <div class="autocomplete-container">
                    <input type="text" id="destination" name="destination" placeholder="Destination" required>
                    <div id="autocomplete-results" class="autocomplete-items"></div>
                </div>
                <div class="date-fields">
                    <input type="date" id="checkInDate" name="checkin" placeholder="Check-in (yyyy-mm-dd)" required>
                    <input type="date" id="checkOutDate" name="checkout" placeholder="Check-out (yyyy-mm-dd)" required>
                </div>
                <select id="room_type" name="room_type" required>
                <option value="">Room Type</option>
                    <option value="1">Single Room</option>
                    <option value="2">Double Room</option>
                    <option value="3">Deluxe Room</option>
                    <option value="4">Suite</option>
                    <option value="5">Executive Room</option>
                    <option value="6">Presidential Suite</option>
                    <option value="7">Family Room</option>
                    <option value="8">Luxury Room</option>
                    <option value="9">Penthouse Suite</option>
                    <option value="10">Garden View Room</option>
                    <option value="11">Ocean View Room</option>
                    <option value="12">Poolside Room</option>
                    <option value="13">Accessible Room</option>
                    <option value="15">Standard Room</option>
                </select>
                <div class="price">
                    <input type="number" id="price" name="price" placeholder="Price Range" required style="width:100%;height:100%;padding:0px;border:none">
                </div>
                <button type="submit">Search</button>
            </div>
        </section>
    </form>
    <?php require "partials/script.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
