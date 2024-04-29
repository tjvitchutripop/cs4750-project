<?php
session_start(); 

include('request-db.php'); 
include('connect-db.php');

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}


if(isset($_POST['isbn13_to_remove']) && isset($_SESSION['userId'])&& isset($_POST['reading_list_id'])) {
    removeFromReadingList($_SESSION['userId'], $_POST['isbn13_to_remove'], $_POST['reading_list_id']);
}


if(isset($_POST['review_id_to_remove']) && isset($_SESSION['userId'])) {
    removeReview($_SESSION['userId'], $_POST['review_id_to_remove']);
    $userReviews = getUserReviews($_SESSION['userId']);
}

$readingLists = getReadingLists($_SESSION['userId']);
$userReviews = getUserReviews($_SESSION['userId']);
$user = getUserName($_SESSION['userId']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Literary Loop | Your Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<?php include("header.php"); ?>

<main class="container" style="margin-top:80px;">
        <h1> Welcome back, <b><?php echo htmlspecialchars($user['first_name']); ?></b>!</h1>
        <section id="reading-list" style="margin-top:30px">
            <h3><b>Reading Lists 📚</b></h3>
            <div class="book-list">
            <?php foreach ($readingLists as $readingList): ?>
                <div class="reading-list">
                    <h4 style="color:gray;"><?php echo htmlspecialchars($readingList['reading_list_title']); ?></h4>
                    <div class="row">  
                        <?php foreach ($readingList['books'] as $book): ?>
                            <div class="col-md-2">
                             <div style="text-align:center">
                                <a href="book.php?isbn13=<?php echo htmlspecialchars($book['isbn13']); ?>">
                                    <img src="<?php echo htmlspecialchars($book['Thumbnail']); ?>" alt="Book Thumbnail">
                                </a>
                                <p style="text-align:center"><?php echo htmlspecialchars($book['title']); ?></p>
                                <!-- Delete form -->
                                <form action="account.php" method="post" style="margin-top:-10">
                                    <input type="hidden" name="isbn13_to_remove" value="<?php echo htmlspecialchars($book['isbn13']); ?>">
                                    <input type="hidden" name="reading_list_id" value="<?php echo htmlspecialchars($book['reading_list_id']); ?>">
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i></button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            </div>
        </section>

    <section id="reviews" style="margin-top:30px">
    <h3><b>Your Reviews</b> ✍️</h3>
    <div class="review-list">
        <?php foreach ($userReviews as $review): ?>
            <div class="card mb-3 shadow-sm">
            <div class="row">
                <div class="col-sm-1">
                <img src="<?php echo htmlspecialchars($review['Thumbnail']); ?>" alt="Book Thumbnail" style="width:100px; height:150px;">
                </div>
                <div class="col-sm-10">
                    <div style="margin-top:60px; margin-left:10px;">
                    <p><?php echo htmlspecialchars($review['content']); ?></p>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div style="margin-top:60px; ">
                        <!-- Link to edit review -->
                        <a class="btn btn-warning btn-sm"href="edit-review.php?review_id=<?php echo $review['review_id']; ?>"><i class="bi bi-pencil-square"></i></a>
                        <!-- Remove Review Form -->
                        <form action="account.php" method="post" style="display: inline;">
                            <input type="hidden" name="review_id_to_remove" value="<?php echo $review['review_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
</main>
</body>
</html>