<?php
session_start();

require("connect-db.php");
require("request-db.php");

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$isbn13 = $_GET["isbn13"] ?? '';
$book = $isbn13 ? getBook($isbn13) : null;
$authors = $isbn13 ? getAuthors($isbn13) : null;

// check if rating and review content are filled out, if not then warn
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $reviewContent = $_POST['reviewContent'];
    if (empty($rating) || empty($reviewContent)){
        $_SESSION['errorMessage'][] = "Please fill out both rating and review.";
    }
    else{
        addReview($_SESSION['userId'], $isbn13, $rating, $reviewContent);
        header("Location: account.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Literary Loop | Review Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<?php include("header.php"); ?>

<body style="margin:80px;">
<?php if (!empty($_SESSION['errorMessage'])): ?>


<div class="alert alert-danger" role="alert">
    <?php foreach ($_SESSION['errorMessage'] as $message): ?>
        <?= htmlspecialchars($message) ?><br>
    <?php endforeach; ?>
    <?php unset($_SESSION['errorMessage']);  ?>
</div>
<?php endif; ?>
    <h1>Share your thoughts 📣</h1>
    <div class="row mt-4">
        <div class="col-sm-3">
            <a href="book.php?isbn13=<?php echo urlencode($book['isbn13']); ?>" style="text-decoration: none; color: inherit;">
            <?php if (!empty($book['Thumbnail'])): ?>
              <img src="<?php echo htmlspecialchars($book['Thumbnail']); ?>" style="width:330px;" alt="Thumbnail">
            <?php else: ?>
              <img src="no-thumbnail.jpg" style="width:330px;" alt="No Thumbnail Available">
             <?php endif; ?>
          </a>
        </div>
        <div class="col-sm-8">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
            <?php foreach($authors as $author) : ?>
                <h3 style="color:gray;"><?php echo $author["author_name"]; ?></h3>
            <?php endforeach; ?>
            <?php if ($book): ?>
        <div class="review-form">
                <form  action="review.php?isbn13=<?php echo htmlspecialchars($isbn13); ?>" method="post">
                    <!-- rating here -->
                    <label for="rating">Rating (1-5):</label>
                    <input class="form-control" type="number" class="" name="rating" min="1" max="5" placeholder="Rating (1-5)">
                    <!-- review content here -->
                    <label for="reviewContent" class="mt-3">Review:</label>
                    <textarea style="height:250px" class="form-control" name="reviewContent" placeholder="Write your review here..."></textarea>
                    <button class="btn btn-primary mt-3" type="submit">Submit Review</button>
                </form>
            </div>
        <?php endif; ?>
        </div>
    
</body>
</head>

</html>
