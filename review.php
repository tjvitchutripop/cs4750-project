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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $reviewContent = $_POST['reviewContent'];

    addReview($_SESSION['userId'], $isbn13, $rating, $reviewContent);

    header("Location: account.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<body>
    <?php if ($book): ?>
        <div class="review-form">
            <img src="<?php echo htmlspecialchars($book['Thumbnail']); ?>" alt="Book Thumbnail">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>

            <form action="review.php?isbn13=<?php echo htmlspecialchars($isbn13); ?>" method="post">
<!-- rating here -->
                <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)">
                <textarea name="reviewContent" placeholder="Write your review here..."></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</head>

</html>
