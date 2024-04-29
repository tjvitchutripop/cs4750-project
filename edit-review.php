<?php
session_start();

require("connect-db.php");
require("request-db.php");

if (!isset($_SESSION['userId']) || !isset($_GET['review_id'])) {
    header("Location: login.php");
    exit();
}

$review_id = $_GET['review_id'];
$review = getReviewById($review_id);


if ((int)$review['user_id'] !== (int)$_SESSION['userId']) {
    echo "You do not have permission to edit this review.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $reviewContent = $_POST['reviewContent'];

    updateReview($review_id, $_SESSION['userId'], $rating, $reviewContent);
    header("Location: account.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Literary Loop | Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<?php include("header.php"); ?>

<body style="margin:80px;">
    <h2>Edit Review 🔧</h2>

    <?php if ($review): ?>
        <form action="edit-review.php?review_id=<?php echo htmlspecialchars($review_id); ?>" method="post">
            <div>
                <label for="rating">Rating:</label>
                <select class="form-control" name="rating" id="rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo (isset($review['number_of_stars']) && $i === $review['number_of_stars']) ? "selected" : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="reviewContent">Review:</label>
                <textarea style="height:250px"class="form-control" id="reviewContent" name="reviewContent"><?php echo htmlspecialchars($review['content']); ?></textarea>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Update Review</button>
        </form>
    <?php endif; ?>

</body>
</html>
