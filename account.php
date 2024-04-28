<?php
session_start(); 

include('request-db.php'); 
include('connect-db.php');

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}


if(isset($_POST['isbn13_to_remove']) && isset($_SESSION['userId'])) {
    removeFromReadingList($_SESSION['userId'], $_POST['isbn13_to_remove']);
}


if(isset($_POST['review_id_to_remove']) && isset($_SESSION['userId'])) {
    removeReview($_SESSION['userId'], $_POST['review_id_to_remove']);
    $userReviews = getUserReviews($_SESSION['userId']);
}

$readingList = getReadingList($_SESSION['userId']);
$userReviews = getUserReviews($_SESSION['userId']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>ACCOUNT</title>
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
<?php include("header.php"); ?>

<main class="container">
        <section id="reading-list">
            <h2>Reading List</h2>
            <div class="book-list">
            <?php foreach ($readingList as $book): ?>
                <div class="book">
                    <a href="book.php?isbn13=<?php echo htmlspecialchars($book['isbn13']); ?>">
                        <img src="<?php echo htmlspecialchars($book['Thumbnail']); ?>" alt="Book Thumbnail">
                    </a>                    <p><?php echo htmlspecialchars($book['title']); ?></p>
                    <!-- Delete form -->
                    <form action="account.php" method="post">
                        <input type="hidden" name="isbn13_to_remove" value="<?php echo htmlspecialchars($book['isbn13']); ?>">
                        <button type="submit" class="btn btn-danger">Remove from Reading List</button>
                    </form>
                </div>
            <?php endforeach; ?>
            </div>
        </section>

        <section id="reviews">
    <h2>My Reviews</h2>
    <div class="review-list">
        <?php foreach ($userReviews as $review): ?>
            <div class="review">
                
                <img src="<?php echo htmlspecialchars($review['Thumbnail']); ?>" alt="Book Thumbnail" style="width:100px; height:150px;">
                <div>
                    <!-- Displaying the user profile picture if it exists -->
                    <img src="<?php echo htmlspecialchars($review['profile_picture'] ?? 'path/to/default/icon.png'); ?>" alt="User Icon" style="width:50px; height:50px;">
                    <span class="user-id"><?php echo htmlspecialchars($review['user_id']); ?></span>
                </div>
                <div>
                    <span class="rating">Rating: <?php echo htmlspecialchars($review['number_of_stars'] ?? 'Not rated'); ?> stars</span>
                    <p><?php echo htmlspecialchars($review['content']); ?></p>
                    <!-- Link to edit review -->
                    <a href="edit-review.php?review_id=<?php echo $review['review_id']; ?>">Edit Review</a>
                    <!-- Remove Review Form -->
                    <form action="account.php" method="post" style="display: inline;">
                        <input type="hidden" name="review_id_to_remove" value="<?php echo $review['review_id']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm">Remove Review</button>
                    </form>
                </div>
                
            </div>
        <?php endforeach; ?>
    </div>
</section>
</main>
</body>
</html>