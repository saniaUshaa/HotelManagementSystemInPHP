<?php 
require 'partials/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $cnic = $_POST['cnic'];
        $hotel_id = $_POST['hotelId'];
        $review = $_POST['review'];
        $rating = $_POST['rating'];
        $alertMsg='NULL';
        // Input validation
        if (empty($cnic) || empty($hotel_id) || empty($review) || $rating <= 0 || $rating > 5) {
            $alertMsg='Invalid input values. Ensure all fields are filled and rating is between 0 and 5.';
        }

        //validate booking
        $sqlValidate="SELECT B_ID FROM Booking where CNICNO=? and HL_ID=?";
        $stmtV=$conn->prepare($sqlValidate);
        $stmtV->bind_param("si",$cnic,$hotel_id);
        $stmtV->execute();
        $resultV = $stmtV->get_result();
        $stmtV->close();
        if($resultV->num_rows==0) {
            $alertMsg='No Booking made in this Hotel!';
        }
        else{
            // SQL query to insert the review
            $sql = "INSERT INTO Feedback (REVIEW, RATING, HL_ID, CNICNO) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sdis', $review, $rating, $hotel_id, $cnic);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $alertMsg='Review Added Successfully';
            } else {
                $alertMsg='Failed to add review. Please try again.';
            }

            $stmt->close();
        }

    } 
    catch (Exception $e) {
        $alertMsg= "Error: " . $e->getMessage();
    }
} 
else {
    $alertMsg= "Error: " . $e->getMessage();
}
header("location:user_account.php?alertMsg=" . urlencode($alertMsg)."&cnic=".urlencode($cnic));


?>