<?php 
session_start(); 

require("connect-db.php"); 
require("request-db.php");
?>

<?php //get book info
$book = getBook($_GET["isbn13"]);
$authors = getAuthors($_GET["isbn13"]);
$reviews = getReviews($_GET["isbn13"]);
$reading_lists = getReadingListID_Title($_SESSION['userId']);
if(isset($_POST['isbn13_to_add']) && isset($_SESSION['userId']) && isset($_POST['reading_list_id'])) {
    // Function to add book to reading list needs to be implemented in request-db.php
    addToReadingList($_SESSION['userId'], $_POST['isbn13_to_add'], $_POST['reading_list_id']);
}

?>

<!DOCTYPE html>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

<body>  
<?php include("header.php"); ?>
<!-- sample add button, need to modify -->
<!-- sample add button -->
<div style="margin-left: 80px;margin-right: 80px;margin-top: 80px;">
<div class="row">
    <div class="col-sm-3">
        <img src="<?php echo $book["Thumbnail"]; ?>" style="width:15vw;"></img>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" style="margin-top:10px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Add to Reading List
            </button>
            <ul class="dropdown-menu">
                <?php foreach($reading_lists as $reading_list) : ?>
                    <li>
                    <form action="book.php?isbn13=<?php echo htmlspecialchars($_GET["isbn13"]); ?>&&reading_list_id=<?php echo $reading_list['reading_list_id']; ?>" method="post">
                        <input type="hidden" name="isbn13_to_add" value="<?php echo htmlspecialchars($_GET["isbn13"]); ?>">
                        <input type="hidden" name="reading_list_id" value="<?php echo $reading_list['reading_list_id']; ?>">
                        <button type="submit" class="dropdown-item"><?php echo $reading_list['reading_list_title']; ?></button>
                    </form>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-sm-8">
        <h1><?php echo $book["title"]; ?></h1>
        <?php foreach($authors as $author) : ?>
            <h2 style="color:gray;"><?php echo $author["author_name"]; ?></h2>
        <?php endforeach; ?>
        <h5>Average Rating: <?php echo $book["Average_rating"]; ?> / 5</h5>
        <p><?php echo $book["Description"]; ?></p>
        </div>
  </div>

<h3 style="margin-top:20px;">Reviews
  <a href="review.php?isbn13=<?php echo htmlspecialchars($_GET["isbn13"]); ?>" class="btn btn-primary">+ Add Review</a>
</h3>
<?php foreach($reviews as $review) : ?>
    <div class="card shadow-sm" style="margin-top:10px;">
        <div class="card-body">
            <h5 class="card-title mb-2"><b><?php echo $review["first_name"]; ?> <?php echo $review["last_name"]; ?></b> says</h5>
            <p class="card-text"><?php echo $review["content"]; ?></p>
            <div class="d-flex ">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-hand-thumbs-up-fill"></i></button><p class="card-text" style="margin-left:10px;"><?php echo $review["likes"]; ?></p>
            </div>
        </div>
    </div>
<?php endforeach; ?>

</div>



