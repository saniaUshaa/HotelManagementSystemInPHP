<?php
$showAlert = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/connectDB.php';
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $password = $_POST["password"];
    $type = $_POST["radio"];
    $name = $fname . " " . $lname;

    // Fetch the maximum SIGNIN_ID
    $sql = "SELECT MAX(SIGNIN_ID) as SIGNIN_ID FROM signin";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $maxSigninId = isset($row['SIGNIN_ID']) ? $row['SIGNIN_ID'] : 0;

    // Generate a unique username
    $username = $fname . $lname . "_" . ($maxSigninId + 1);

    // Insert into SIGNIN table
    $sql = "INSERT INTO `SIGNIN`(`username`, `password`, `ROLE`) VALUES ('$username', '$password', '$type')";
    if (mysqli_query($conn, $sql)) {
        // Fetch the new SIGNIN_ID
        $SIGNID = $conn->insert_id;

        if ($type == "admin") {
            // Insert into ADMIN table
            $sql = "INSERT INTO `ADMIN`(`A_NAME`, `SIGNIN_ID`) VALUES ('$name', '$SIGNID')";
            if (mysqli_query($conn, $sql)) {
                $showAlert = true;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
        else{
            $showAlert=true;
        }
    } 
    else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SignUp Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .signin{
            border:solid 2px black ;
            padding:20px;
            margin:auto;
            width:800px;
            height:500px;
            border-radius:20px;
        }
        .signin:hover{
            box-shadow: 10px 10px 20px rgba(36, 36, 36, 0.5);
        }
        .header{
            text-align:center;
            margin-top:30px;
        }
        body{
            background-color:#B0C4DE;
        }
    </style>
</head>
  <body>
  <?php require 'partials/nav.php'?>
  <?php
  if($showAlert){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Account created, you can now login. Your user name is <strong>'.$username.'</strong>.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
   }
  ?>
    <div class="header">
    <h3>Sign In to explore more</h3>
    </div>
    <div class="signin">
        <?php require 'partials/signupform.php'?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>