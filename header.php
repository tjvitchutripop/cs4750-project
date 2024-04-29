<?php 
session_start(); 
if (!isset($_SESSION['userId'])) {
  $userDisplay = "Log In / Sign up";
} else {
  $userDisplay = "Your Account"; // Display user ID for verification
}
?>

<header>  
  <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">            
      <a class="navbar-brand" href="index.php">Literary Loop 📖♾️</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <!-- check if currently logged in, display Log out button 
               otherwise, display sign up and log in buttons -->
          <?php if (!isset($_SESSION['userId'])) { ?>              
            <li class="nav-item">
              <a class="nav-link" href="login.php">Log In / Sign up</a>
            </li>              
          <?php  } else { ?>                    
            <li class="nav-item">                  
              <a class="nav-link" href="account.php"> <?php echo $userDisplay; ?></a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>    

        
<!-- for testing purpose, set this, will delete this -->
          <!-- <li class="nav-item">
              <a class="nav-link" href="login.php">TEST: Log In / Sign up</a>
            </li>   
            
            <li class="nav-item">                  
              <a class="nav-link" href="account.php">TEST: Account</a>
            </li> -->
        </ul>
      </div>
    </div>
  </nav>
</header>    