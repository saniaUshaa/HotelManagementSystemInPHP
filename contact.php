<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #003366;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .navbar .nav-links {
            list-style: none;
            display: flex;
        }

        .navbar .nav-links li {
            margin: 0 1rem;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
        }

        .btn-get-started {
            background-color: #0055a5;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
        }

        .booking-section {
            padding: 2rem;
            text-align: center;
        }

        .search-container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #003366;
            color: white;
            cursor: pointer;
            border: none;
        }

        .offers-section {
            display: flex;
            justify-content: center;
            padding: 2rem;
            gap: 1.5rem;
        }

        .offer-box {
            background-color: white;
            width: 30%;
            padding: 1.5rem;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .offer-box h3 {
            margin-bottom: 1rem;
        }

        .offer-box p {
            font-size: 1rem;
            color: #555;
        }

        .discount-banner {
            background-color: #ff6600;
            color: white;
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
    <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES </div>
    <ul class="nav-links">
            <li><a href="searchHotel.php">Hotels</a></li>
            <li><a href="home.php" class="btn-get-started">Get Started</a></li>
            <li><a href="policy.php" class="btn-get-started">View Policy</a></li>
        </ul>
    </nav>

    
    <!-- Discount Banner -->
    <div class="discount-banner">
        <h2>Limited Time Offer: 20% Off on All Bookings!</h2>
        <p>Book your stay before December 31st and save big on luxury and budget rooms.</p>
    </div>

    <!-- Offers Section -->
    <section class="offers-section">
        <div class="offer-box">
            <h3>Luxury Package</h3>
            <p>Enjoy a 5-star experience with top-tier amenities. Includes free spa access and breakfast.</p>
            <button>View Offer</button>
        </div>
        <div class="offer-box">
            <h3>Family Package</h3>
            <p>Special discounts for families. Complimentary breakfast and kid-friendly activities included.</p>
            <button>View Offer</button>
        </div>
        <div class="offer-box">
            <h3>Weekend Getaway</h3>
            <p>Perfect for short stays! Includes late check-out and welcome drinks.</p>
            <button>View Offer</button>
        </div>
    </section>

    <?php require "partials/script.php" ?>
</body>
</html>