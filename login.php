<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>LOGIN</title>
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
    
  <main class="form-signin w-100 m-auto">
<!-- should change action="request.php" maybe -->
    <form action="request.php" method="POST"> 
    <h1 class="h3 mb-3 fw-normal">Welcome!</h1>
    <div class="col-xs-12">
        <?= isset($message) ? $message : '' ?>
    </div>



      <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" name="phonenumber" placeholder="Phone Number">
        <label for="floatingInput">USER ID</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <div class="form-check text-start my-3">
          <button type="submit" class="btn btn-primary py-2" style="width: 48%;">Log In</button>
          <a href="signup.php" class="btn btn-outline-primary py-2" style="width: 48%;">Sign up</a>


  </form>
  
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>
<script>
document.getElementById('signupBtn').addEventListener('click', function () {
  window.location.href = 'signup.php';
});
</script>
</body>

</html>
</body>