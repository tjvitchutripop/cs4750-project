<?php 
session_start(); 

require("connect-db.php");    // include("connect-db.php");
require("request-db.php");
?>


<?php   // form handling

$list_of_requests = getAllRequests();
// var_dump($list_of_requests);   // debug
  
if ($_SERVER['REQUEST_METHOD'] == 'POST')   // GET
{
  if (!empty($_POST['addBtn']))    // $_GET['....']
  {
      addRequests($_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']);
      $list_of_requests = getAllRequests();
  }
  else if (!empty($_POST['updateBtn']))
  {  
      // get reqId
      $request_to_update = getRequestById($_POST['reqId']);
      // var_dump($request_to_update );
  }   
  else if (!empty($_POST['cofmBtn']))
  {
     // echo $_POST['cofm_reqId'] . ", " . $_POST['requestedDate'] .", " .  $_POST['roomNo'] .", " .  $_POST['requestedBy'] .", " .  $_POST['requestDesc'] .", " .  $_POST['priority_option']; 
     updateRequest($_POST['cofm_reqId'], $_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']); 
     $list_of_requests = getAllRequests();
  }
  else if (!empty($_POST['deleteBtn']))
  {
    deleteRequest($_POST['reqId']);
    $list_of_requests = getAllRequests();
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Maintenance request form, a small/toy web app for ISP homework assignment, used by CS 3250 (Software Testing)">
  <meta name="keywords" content="CS 3250, Upsorn, Praphamontripong, Software Testing">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Literary Loop</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>

<body>  
<?php include("header.php"); ?>

<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Literary Loop</h2>
    </div>  
  </div>
  
  <!---------------->

  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Requested date:
            <input type='text' class='form-control' 
                   id='requestedDate' name='requestedDate' 
                   placeholder='Format: yyyy-mm-dd' 
                   pattern="\d{4}-\d{1,2}-\d{1,2}" 
                   value="<?php if ($request_to_update != null) echo $request_to_update['reqDate'] ?>" />
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Room Number:
            <input type='text' class='form-control' id='roomNo' name='roomNo' 
            value="<?php if ($request_to_update != null) echo $request_to_update['roomNumber'] ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested by: 
            <input type='text' class='form-control' id='requestedBy' name='requestedBy'
                   placeholder='Enter your name'
                   value="<?php if ($request_to_update != null) echo $request_to_update['reqBy'] ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class="mb-3">
            Description of work/repair:
            <input type='text' class='form-control' id='requestDesc' name='requestDesc'
            value="<?php if ($request_to_update != null) echo $request_to_update['repairDesc'] ?>" />
        </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested Priority:
            <select class='form-select' id='priority_option' name='priority_option'>
              <option selected></option>
              <option value='high' <?php if ($request_to_update!=null && $request_to_update['reqPriority']=='high') echo ' selected="selected"' ?> >
                High - Must be done within 24 hours</option>
              <option value='medium' <?php if ($request_to_update!=null && $request_to_update['reqPriority']=='medium') echo ' selected="selected"' ?> >
                Medium - Within a week</option>
              <option value='low' <?php if ($request_to_update!=null && $request_to_update['reqPriority']=='low') echo ' selected="selected"' ?> >
                Low - When you get a chance</option>
            </select>
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Submit a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid ">
      <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
           title="Update a maintenance request" />      
      <input type="hidden" value="<?= $_POST['isbn13'] ?>" name="cofm_reqId" />      
      <!-- Why need to attach this cofm_reqId? 
           Because of HTTP stateless property, $_POST['reqId'] is available to this request only. 
           To carry over the reqId to the next round of form submision, need to pass a token to the next request. 
      -->
      </div>	    
      <div class="col-4 d-grid">
        <input type="reset" value="Clear form" name="clearBtn" id="clearBtn" class="btn btn-secondary" />
      </div>      
    </div>  
    <div>
  </div>  
</form>

</div>


</div>



</div>

<hr/>
<div class="container">
  <h3>Books</h3>
  <form action="" method="GET">
      <label for="sort">Sort by:</label>
      <select name="sort" id="sort">
          <option value="title_asc">Title (0-9, A-Z)</option>
          <option value="rating_asc">Rating (Low to High)</option>
          <option value="rating_desc">Rating (High to Low)</option>
      </select>
      <button type="submit">Sort</button>
  </form>
  <div class="row justify-content-center">  
  <?php 
  // Sort the list of requests if the form is submitted
  if (isset($_GET['sort'])) {
    $sort_option = $_GET['sort'];
    switch ($sort_option) {
      case 'title_asc':
        // Sort by title A-Z
        usort($list_of_requests, function($a, $b) {
          return strcmp($a['title'], $b['title']);
        });
        break;
      case 'rating_asc':
        // Sort by rating Low to High
        usort($list_of_requests, function($a, $b) {
          return ($a['Average_rating'] < $b['Average_rating']) ? -1 : 1;
        });
        break;
      case 'rating_desc':
        // Sort by rating High to Low
        usort($list_of_requests, function($a, $b) {
          return ($a['Average_rating'] < $b['Average_rating']) ? 1 : -1;
        });
        break;
      default:
        // Default sorting if invalid option is selected
        usort($list_of_requests, function($a, $b) {
          return strcmp($a['title'], $b['title']);
        });
        break;
  }
}
      // Display sorted or original list of requests
      foreach ($list_of_requests as $req_info): ?>
      <div class="col-md-2">
        <div class="book">
          <a href="book.php?isbn13=<?php echo urlencode($req_info['isbn13']); ?>" style="text-decoration: none; color: inherit;">
              <img src="<?php echo htmlspecialchars($req_info['Thumbnail']); ?>" alt="Thumbnail">
              <p class="title"><?php echo htmlspecialchars($req_info['title']); ?></p>
          </a>
        </div>
      </div>
    <?php endforeach; ?>  
  </div>   
</div>   

<style>
.container {
    margin-top: 20px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.book {
    text-align: center;
    margin: 10px;
}

.book img {
    width: 150px; /* Adjust size as needed */
    height: 200px; /* Adjust size as needed */
    object-fit: cover; /* Ensure the image fills the designated space */
}

.title {
    margin-top: 10px;
    font-weight: bold;
}
</style>

<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>