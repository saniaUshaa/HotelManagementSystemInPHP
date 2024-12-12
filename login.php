<?php
$showAlert=0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/connectDB.php';
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    $login=false;
    $stmt = $conn->prepare("SELECT * FROM SIGNIN WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $res = $result->fetch_assoc();
        $login = true;
        $showAlert = 2;
        session_start();
        session_regenerate_id(); 
        
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        // Redirect based on user role
        if ($res["ROLE"] == "admin") {
            header("location: manage.php");
        } else {
            header("location: searchHotel.php");
        }
        exit();
    } 
    else {
        $showAlert = 1;
    }

    $stmt->close();
    $conn->close();
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LoginForm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .login{
            
            border:solid 2px black ;
            margin:auto;
            padding:20px;
            width:700px;
            height:400px;
            border-radius:20px;
            display:flex;
            flex-direction: column;
            justify-content: center;
        }
        .login:hover{
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
  if($showAlert==1){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Incorrect Username or password. Try again!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
   }
   elseif($showAlert==2){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Logged In successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
   }
  ?>
    <div class="header">
    <h3>Login In to explore more</h3>
    </div>
    <div class="login">
        <?php require 'partials/loginform.php'?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>