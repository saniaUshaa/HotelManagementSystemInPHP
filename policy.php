


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Policies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
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

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #1E90FF;
        }

        ul {
            margin: 1rem 0;
            padding: 0;
            list-style: none;
        }

        ul li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #ddd;
        }

        ul li:last-child {
            border-bottom: none;
        }

        ul li i {
            margin-right: 0.5rem;
            color: #1E90FF;
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo"><i class="fa-solid fa-hotel"></i> SES</div>
        <ul class="nav-links">
        <li><a href="contact.php" class="btn-get-started">Back</a></li>
      </ul>
    </nav>

    <div class="container">
    <h1>Hotel Policies</h1>
    <ul>
        <li><i class="fa-solid fa-check-circle"></i> Check-in Time: 2:00 PM</li>
        <li><i class="fa-solid fa-check-circle"></i> Check-out Time: 11:00 AM</li>
        <li><i class="fa-solid fa-check-circle"></i> Cancellation Policy: Free cancellation up to 24 hours before check-in.</li>
        <li><i class="fa-solid fa-check-circle"></i> No Smoking Policy: All rooms are non-smoking. A cleaning fee applies for violations.</li>
        <li><i class="fa-solid fa-check-circle"></i> Pet Policy: Pets are allowed with a surcharge of $25 per pet per night.</li>
        <li><i class="fa-solid fa-check-circle"></i> Payment Policy: We accept Visa, MasterCard, and American Express.</li>
        <li><i class="fa-solid fa-check-circle"></i> Quiet Hours: Please maintain quiet hours from 10:00 PM to 8:00 AM.</li>
    </ul>
    

</div>


    <footer>
        © 2024 SES Hotel Management System. All rights reserved.
    </footer>
    <?php require "partials/script.php" ?>
</body>
</html>
