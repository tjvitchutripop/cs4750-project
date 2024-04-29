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
<hr/>


<div class="container">
  <h3>Explore a universe of books 🌌</h3>  
  <div class="row justify-content-center">
      <div class="col-md-7">
          <div class="card mt-5">
              <div class="card-header text-left">
                  <h4>Search For A Book</h4>
              </div>
              <div class="card-body">
              Title/Name/isbn13:
                  <form action="" method="GET">
                      <div class="row"> 
                          <div class="col-md-8">
                              <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" class="form-control">
                          </div>
                          <div class="col-md-4">
                              <button type="submit" class="btn btn-primary">Search</button>
                          </div>
                      </div>
                      <tr>
                        <td colspan=1>
                          <div class='mb-3'>
                            Search Type:
                            <select class='form-select' id='search_type' name='search_type' style="height:40px; width:100px">
                              <option selected></option>
                              <option value='title'>
                                Title</option>
                              <option value='isbn13'>
                                isbn13</option>
                              <option value='author'>
                                Author</option>
                            </select>
                          </div>
                        </td>
                      </tr>
                  </form>
                  <div class="row">
                      <div class="col-md-12">
                          <hr>
                          <?php 
                              if(isset($_GET['search']))
                              {
                                  $search = $_GET['search'];
                                  $selectOption = $_GET['search_type'];
                                  if(empty($search) && empty($selectOption))
                                  {
                                      echo "You need to fill all Fields";
                                  }
                                  else {
                                    if($selectOption == 'title') {
                                      $query = getTitle($search);
                                    }
                                    if($selectOption == 'isbn13') {
                                      $query = getBook($search);
                                    }
                                    if($selectOption == 'author') {
                                      $query = getBookFromAuthors($search);
                                    }
                                    if($query)
                                    {
                                      foreach ($query as $req_info): ?>
                                        <div class="col-md-2">
                                          <div class="book">
                                            <a href="book.php?isbn13=<?php echo urlencode($req_info['isbn13']); ?>" style="text-decoration: none; color: inherit;">
                                                <img src="<?php echo htmlspecialchars($req_info['Thumbnail']); ?>" alt="Thumbnail">
                                                <p class="title"><?php echo htmlspecialchars($req_info['title']); ?></p>
                                            </a>
                                          </div>
                                        </div>
                                      <?php endforeach; ?> 
                                      <?php
                                    }
                                    else {
                                      echo "No Books Found";
                                    }

                                  }      
                              }       
                          ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <form action="" method="GET" style="margin-top: 20px;">
      <div style="display:flex;">
        <label for="sort" style="margin-top:5px;">Sort by:</label>
        <select class="form-select" style="width: 200px; margin-left:10px; margin-right:5px;" name="sort" id="sort">
          <option value="title_asc" <?php echo ($_GET['sort'] ?? '') === 'title_asc' ? 'selected' : ''; ?>>Title (0-9, A-Z)</option>
          <option value="rating_asc" <?php echo ($_GET['sort'] ?? '') === 'rating_asc' ? 'selected' : ''; ?>>Rating (Low to High)</option>
          <option value="rating_desc" <?php echo ($_GET['sort'] ?? '') === 'rating_desc' ? 'selected' : ''; ?>>Rating (High to Low)</option>
        </select>
        <button class="btn btn-primary btn-sm" type="submit">Sort</button>
      </div>
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
              <img class="card shadow-md" src="<?php echo htmlspecialchars($req_info['Thumbnail']); ?>" alt="Thumbnail">
              <p class="title"><?php echo htmlspecialchars($req_info['title']); ?></p>
          </a>
        </div>
      </div>
    <?php endforeach; ?>  
  </div>   
</div>   

<style>
.container {
    margin-top: 50px;
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