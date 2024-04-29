<?php 
session_start(); 

require("connect-db.php"); 
require("request-db.php");
?>

<?php //get book info
$book = getBook($_GET["isbn13"]);
$authors = getAuthors($_GET["isbn13"]);
$reviews = getReviews($_GET["isbn13"]);
// get comments for each review
foreach($reviews as $review) {
    $review["comments"] = getCommentsForReview($review["review_id"]);
}
$reading_lists = getReadingListID_Title($_SESSION['userId']);
$numberofreads = getBookReads($_GET["isbn13"]);
if(isset($_POST['isbn13_to_add']) && isset($_SESSION['userId']) && isset($_POST['reading_list_id'])) {
    // Function to add book to reading list needs to be implemented in request-db.php
    addToReadingList($_SESSION['userId'], $_POST['isbn13_to_add'], $_POST['reading_list_id']);
    // Redirect to the account page
    header("Location: account.php");
}
if(isset($_POST['commentContent']) && isset($_POST['review_id'])) {
    addComment($_SESSION['userId'], $_POST['review_id'], $_POST['commentContent']);
    header("Location: book.php?isbn13=".$_GET["isbn13"]);
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
<div style="margin-left: 80px;margin-right: 80px;margin-top: 120px;">
<div class="row">
    <div class="col-sm-3">
        <img src="<?php echo $book["Thumbnail"]; ?>" style="width:15vw;"></img>
        <!-- If logged in can add to reading list -->
        <?php if(isset($_SESSION['userId'])) : ?>
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
        <?php endif; ?>
    </div>
    <div class="col-sm-8">
        <h1><?php echo $book["title"]; ?></h1>
        <?php foreach($authors as $author) : ?>
            <h2 style="color:gray;"><?php echo $author["author_name"]; ?></h2>
        <?php endforeach; ?>
        <h5>Average Rating: <?php echo $book["Average_rating"]; ?> / 5</h5>
        <p><?php echo $book["Description"]; ?></p>
        <h5>How many people have Read this Book? <?php echo $numberofreads["num"]; ?></h5>
        </div>
  </div>

<div style="display:flex; justify-content:space-between;">
    <h3 style="margin-top:20px;">Reviews</h3>
    <a href="review.php?isbn13=<?php echo htmlspecialchars($_GET["isbn13"]); ?>" style="height:40px; margin-top:15px;" class="btn btn-primary">+ Add Review</a>
</div>
<?php foreach($reviews as $review) : ?>
    <div class="card shadow-sm" style="margin-top:10px;">
        <div class="card-body">
            <h5 class="card-title mb-2"><b><?php echo $review["first_name"]; ?> <?php echo $review["last_name"]; ?></b> says</h5>
            <p class="card-text"><?php echo $review["content"]; ?></p>
            <div style="display:flex; justify-content:space-between;">
                <div class="d-flex">
                    <?php if(isset($_SESSION['userId'])) : ?>
                    <button class="btn btn-outline-primary" style="margin-right:10px;" type="submit"><i class="bi bi-hand-thumbs-up-fill"></i></button>
                    <?php endif; ?>
                    <p class="card-text mt-2"><?php echo $review["likes"]; ?> Likes</p>
                </div>
                <!-- Modal for Comment -->
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#commentModal<?php echo $review["review_id"]; ?>">
                        Reply
                    </button>
                    <div class="modal fade" id="commentModal<?php echo $review["review_id"]; ?>" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentModalLabel">Reply to <?php echo $review["first_name"]; ?>'s Review</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="book.php?isbn13=<?php echo htmlspecialchars($_GET["isbn13"]); ?>" method="post">
                                        <input type="hidden" name="review_id" value="<?php echo $review["review_id"]; ?>">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="commentContent" placeholder="Write your comment here..."></textarea>
                                            <label for="commentContent">Comment</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Submit Comment</button>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- Display Comments with Comemnter's First Name and Last Name -->
    <!-- Check if review has comments -->
    <?php foreach($review["comments"] as $comment) : ?>
        <div class="card shadow-sm" style="margin-top:10px; margin-left:20px;">
            <div class="card-body">
                <h5 class="card-title
                mb-2"><b><?php echo $comment["first_name"]; ?> <?php echo $comment["last_name"]; ?></b> says</h5>
                <p class="card-text"><?php echo $comment["content"]; ?></p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

</div>



