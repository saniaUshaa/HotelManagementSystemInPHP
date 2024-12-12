<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php require "partials/hotelstyles.php"?>

    <!-- <nav class="navbar">
    <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES </div>
    <ul class="nav-links">
            <li><a href="hotelList.php">Home</a></li>
            <li><a href="content.php">View Offers</a></li>
        </ul>
    </nav> -->
    <nav class="navbar">
        <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES</div>
        <ul class="nav-links">
        <li><a href="searchHotel.php">Home</a></li>
        <li><a href="contact.php">View Offers</a></li>
        </ul>
    </nav>

    <section class="hotel-description">
        <h1>Luxury Hotels</h1>
        <p>
            Experience world-class service and luxurious comfort in our 5-star hotels located across the globe. Whether you’re traveling for business or leisure, we provide premium accommodations tailored to meet your needs.
        </p>
        <div class="hotel-gallery">
            <img src="https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
             alt="Luxury Hotel" width="300">
            <img src="https://images.pexels.com/photos/2506988/pexels-photo-2506988.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Hotel 2" width="300">
            <img src="https://images.pexels.com/photos/210604/pexels-photo-210604.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Hotel 3" width="300">
        </div>
    </section>
</body>
</html>