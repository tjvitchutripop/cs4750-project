<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>SIGNUP</title>
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
<body>
<h1>hi</h1>
<div class = "container">
  <!-- Sign up section -->
  
  <section>
    <div class="py-5 text-center">
            <h2>Sign up</h2>
            <p>Fill out your information to make an account.</p>
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
        
      
        <form action="request.php" method="POST">
          <div class="form-group  col-12">
            <label for="first_name">First name</label>
            <input type="text" class="form-control" name="first_name" placeholder="First name" required> 
          </div>
          <div class="form-group">
            <label for="last_name">Last name</label>
            <input type="text" class="form-control" name="last_name" placeholder="Last name" required> 
          </div>
          <div class="form-group">
            <label for="userID">User ID</label>
            <input type="userID" class="form-control" name="userID" placeholder="User ID" required> 
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password" required> 
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" required> 
          </div>
          <button type="submit" class="btn btn-primary">Sign up</button>
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