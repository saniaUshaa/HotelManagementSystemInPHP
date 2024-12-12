<?php
require 'partials/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnic = $_POST['cnic'] ?? '';
    $currentUrl = $_SERVER['REQUEST_URI']; // Get the current page URL

    try {
        // Validate CNIC
        $sql = "SELECT CNICNO FROM Customer WHERE CNICNO = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cnic);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // Check if the CNIC exists
        if ($result->num_rows > 0) {
            // Redirect to user account page with CNIC
            header("Location: user_account.php?cnic=" . urlencode($cnic));
            exit();
        } else {
            // If CNIC doesn't match, redirect back to the same page with an error message
            header("Location: $currentUrl");
            exit();
        }
    } catch (Exception $e) {
        // Handle exceptions
        $error = "Error: " . $e->getMessage();
    }
}

$error = $_GET['error'] ?? ''; // Retrieve error message from URL if available
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Booking</title>
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
   
    </style>
    <script>
        function askForCNIC() {
            const cnic = prompt("Please enter your CNIC:");
            if (cnic) {
                document.getElementById('cnicInput').value = cnic;
                document.getElementById('cnicForm').submit();
            }
        }
    </script>
</head>
<body>
    <?php if ($error): ?>
        <script>
            alert("<?php echo htmlspecialchars($error); ?>");
        </script>
    <?php endif; ?>

    <header>
    <nav class="navbar">
    <div class="logo"><i class="fa-solid fa-hotel fa"></i> SES</div>
    </nav>

        <nav>
            <a href="hoteldesc.php">Hotels</a>
            <a href="contact.php">View Offers</a>
            <a href="javascript:void(0);" onclick="askForCNIC()">My Account</a>
            <a href="login.php">Log-out</a>
        </nav>
    </header>

    <!-- Hidden form for CNIC submission -->
    <form id="cnicForm" method="POST" action="">
        <input type="hidden" id="cnicInput" name="cnic">
    </form>
</body>
</html>
