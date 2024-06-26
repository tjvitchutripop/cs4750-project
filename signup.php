<?php
session_start(); 

require("connect-db.php");
require("request-db.php");

$admin_key = "password";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!filter_var($_POST['userId'], FILTER_VALIDATE_INT) || strlen((string)$_POST['userId']) != 9) {
        // echo "User ID must be a 9-digit number.";
        $_SESSION['errorMessage'][] = "User ID must be a 9-digit number.";

    }  elseif ($_POST['password'] !== $_POST['confirm_password']) {
        // echo "Passwords do not match.";
        $_SESSION['errorMessage'][] = "Passwords do not match.";
    } 
    elseif ($_POST['admin']!= "" && $_POST['admin'] !== $admin_key) {
        $_SESSION['errorMessage'][] = "Invalid admin key.";
    }
    elseif ($_POST['admin']!= "" && $_POST['admin'] === $admin_key) {
      $userAdded = addUser($_POST['first_name'], $_POST['last_name'], $_POST['userId'], $_POST['password'], 1);
      header("Location: index.php");  // Redirect to page if user is added
      exit();
  }
    else {
      $userAdded = addUser($_POST['first_name'], $_POST['last_name'], $_POST['userId'], $_POST['password'], 0);
      header("Location: index.php");  // Redirect to page if user is added
      exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Literary Loop | Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/main.css">
  <style>
    html {
      min-height: 100%;
      display: flex;
    }

    body {
      margin: auto;
    }

    form {
      width: 330px;
      padding: 1rem;

    }
  </style>
</head>
<!-- Add header -->
<?php include("header.php"); ?>
<body style="margin-top:100px">
<div class = "container">
  <!-- Sign up section -->
  
  <section>
    <div class="mt-5 text-left">
            <h2>Create Your Account</h2>
            <p> Get ready to join a global community of avid readers ✨</p>
        </div>

        <!-- FOR ERROR MESSAGE DISPLAYING -->
    <?php if (!empty($_SESSION['errorMessage'])): ?>


      <div class="alert alert-danger" role="alert">
          <?php foreach ($_SESSION['errorMessage'] as $message): ?>
              <?= htmlspecialchars($message) ?><br>
          <?php endforeach; ?>
          <?php unset($_SESSION['errorMessage']);  ?>
      </div>
    <?php endif; ?>
    <div class="row g-5">
      <div class="info-box">
        
      
        <form action="signup.php" method="POST">
          <div class="form-group  col-12">
            <label for="first_name">First name</label>
            <input type="text" class="form-control" name="first_name" placeholder="First name" required> 
          </div>
          <div class="form-group">
            <label for="last_name">Last name</label>
            <input type="text" class="form-control" name="last_name" placeholder="Last name" required> 
          </div>
          <div class="form-group">
            <label for="userId">User ID</label>
            <input type="userId" class="form-control" name="userId" placeholder="User ID" required> 
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholdaer="Password" required> 
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" required> 
          </div>
          <div class="form-group">
            <label for="admin">If you're an admin, please fill out the admin key:</label>
            <input type="password" class="form-control" name="admin" placeholder="Admin Key">
          <button type="submit" class="btn btn-primary mt-3">Sign up</button>
        </form>

      </div>
    </div>
        <div class="col-md-3 mb-3">
          

          <div class="invalid-feedback">
            Password matches: {YES OR NO}
          </div>
        </div>



      </div>
      
  </section>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>