<?php
include 'partials/connectDB.php'; // Ensure database connection

$sql = "

CREATE TRIGGER validate_hotel_service_insert
BEFORE INSERT ON Hotel_Services
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Hotel WHERE HL_ID = NEW.HL_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hotel ID does not exist in Hotel table.';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM Services WHERE S_ID = NEW.S_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Service ID does not exist in Services table.';
    END IF;
END;
";

if (!mysqli_query($conn, $sql)) {
    echo "Error creating trigger: " . mysqli_error($conn) . "<br>";
}

$sql = "
CREATE TRIGGER validate_hotel_room_insert
BEFORE INSERT ON Hotel_Room
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Hotel WHERE HL_ID = NEW.HL_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hotel ID does not exist in Hotel table.';
    END IF;
END;
";

if (!mysqli_query($conn, $sql)) {
    echo "Error creating trigger: " . mysqli_error($conn) . "<br>";
}

$sql = "
CREATE TRIGGER validate_employee_insert
BEFORE INSERT ON Employees
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Hotel WHERE HL_ID = NEW.HL_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The specified Hotel ID does not exist. Please provide a valid Hotel ID.';
    END IF;
END;
";

if (!mysqli_query($conn, $sql)) {
    echo "Error creating trigger: " . mysqli_error($conn) . "<br>";
}

$sql = "
CREATE TRIGGER prevent_duplicate_feedback
BEFORE INSERT ON Feedback
FOR EACH ROW
BEGIN
    IF EXISTS (SELECT 1 FROM Feedback WHERE HL_ID = NEW.HL_ID AND CNICNO = NEW.CNICNO) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Feedback already exists for this customer and hotel.';
    END IF;
END;
";

if (!mysqli_query($conn, $sql)) {
    echo "Error creating trigger: " . mysqli_error($conn) . "<br>";
}

$conn->close();


?>
