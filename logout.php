<!-- Log Out Functionality -->
<?php
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
