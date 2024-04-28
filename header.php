<?php session_start(); ?>

<header>  
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">            
      <a class="navbar-brand" href="#">Literary Loop 📖</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <!-- check if currently logged in, display Log out button 
               otherwise, display sign up and log in buttons -->
          <?php if (!isset($_SESSION['username'])) { ?>              
            <li class="nav-item">
              <a class="nav-link" href="login.php">Log In / Sign up</a>
            </li>              
          <?php  } else { ?>                    
            <li class="nav-item">                  
              <a class="nav-link" href="account.php">Account</a>
            </li>
          <?php } ?>
        
          
        </ul>
      </div>
    </div>
  </nav>
</header>    