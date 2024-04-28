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
    $readingList = getReadingList($_SESSION['userId']);
}
$readingList = getReadingList($_SESSION['userId']);
$userReviews = getUserReviews($_SESSION['userId']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>LOGIN</title>
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
                    <img src="<?php echo htmlspecialchars($book['Thumbnail']); ?>" alt="Book Thumbnail">
                    <p><?php echo htmlspecialchars($book['title']); ?></p>
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
                        <img src="<?php echo htmlspecialchars($review['Thumbnail']); ?>" alt="Book Thumbnail">
                        <div>
                            <img src="<?php echo htmlspecialchars($review['profile_picture']); ?>" alt="User Icon">
                            <span class="user-id"><?php echo htmlspecialchars($review['user_id']); ?></span>
                        </div>
                        <div>
                            <span class="rating">Rating: <?php echo htmlspecialchars($review['number_of_stars']); ?> stars</span>
                            <p><?php echo htmlspecialchars($review['content']); ?></p>
                            <!-- Link to edit review -->
                            <a href="edit-review.php?review_id=<?php echo $review['review_id']; ?>">Edit Review</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
</main>
</body>
</html>