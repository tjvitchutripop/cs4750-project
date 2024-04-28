<?php
session_start();

require("connect-db.php");
require("request-db.php");

// Redirect if not logged in or if no review ID is provided.
if (!isset($_SESSION['userId']) || !isset($_GET['review_id'])) {
    header("Location: login.php");
    exit();
}

$review_id = $_GET['review_id'];
$review = getReviewById($review_id);


if ($review['user_id'] !== $_SESSION['userId']) {
    echo "You do not have permission to edit this review.";
    exit();
}

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $reviewContent = $_POST['reviewContent'];

    updateReview($review_id, $_SESSION['userId'], $rating, $reviewContent);

    // Redirect to account page after submission
    header("Location: account.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <!-- Add your stylesheets here -->
</head>
<body>
    <h2>Edit Review</h2>

    <?php if ($review): ?>
        <form action="edit-review.php?review_id=<?php echo htmlspecialchars($review_id); ?>" method="post">
            <div>
                <label for="rating">Rating:</label>
                <select name="rating" id="rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == $review['number_of_stars'] ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="reviewContent">Review:</label>
                <textarea id="reviewContent" name="reviewContent"><?php echo htmlspecialchars($review['content']); ?></textarea>
            </div>
            <button type="submit">Update Review</button>
        </form>
    <?php endif; ?>

</body>
</html>
