<?php 
require("connect-db.php"); 
require("request-db.php");
?>

<?php //get book info
$book = getBook($_GET["isbn13"]);
$authors = getAuthors($_GET["isbn13"]);
$reviews = getReviews($_GET["isbn13"]);

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
    <link rel="stylesheet" href="maintenance-system.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

<body>  
<?php include("header.php"); ?>
<div style="margin-left: 80px;margin-right: 80px;margin-top: 40px;">
<div class="row">
    <div class="col-sm-3">
        <img src="<?php echo $book["Thumbnail"]; ?>" style="width:15vw;"></img>
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
<h3 style="margin-top:20px;">Reviews</h3>
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



