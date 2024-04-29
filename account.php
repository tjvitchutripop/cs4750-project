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

if(isset($_POST['reading_list_id_to_remove']) && isset($_SESSION['userId'])) {
    deleteReadingList($_SESSION['userId'], $_POST['reading_list_id_to_remove']);
}


if(isset($_POST['review_id_to_remove']) && isset($_SESSION['userId'])) {
    removeReview($_SESSION['userId'], $_POST['review_id_to_remove']);
    $userReviews = getUserReviews($_SESSION['userId']);
}

if(isset($_POST['reading_list_id']) && isset($_POST['reading_list_title']) && isset($_SESSION['userId'])) {
    renameReadingList($_SESSION['userId'], $_POST['reading_list_id'], $_POST['reading_list_title']);
}
else if(isset($_POST['reading_list_title']) && isset($_SESSION['userId'])) {
    createReadingList($_SESSION['userId'], $_POST['reading_list_title']);
}

$readingLists = getReadingLists($_SESSION['userId']);
$userReviews = getUserReviews($_SESSION['userId']);
$user = getUserName($_SESSION['userId']);
$readList = getBookReadByUser($_SESSION['userId']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Literary Loop | Your Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<?php include("header.php"); ?>

<main class="container" style="margin-top:80px;">
        <h1> Welcome back, <b><?php echo htmlspecialchars($user['first_name']); ?></b>!</h1>
        <p>🪪 Your User ID is <b><?php echo htmlspecialchars($_SESSION['userId']); ?></b></p>
        <section id="reading-list" style="margin-top:30px">
        <div style="display:flex; justify-content:space-between;">
            <h3><b>Reading Lists 📚</b></h3>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal">
            Create Reading List
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Reading Lists</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="account.php" method="post">
                        <div class="mb-3">
                            <label for="reading_list_title" class="form-label">Reading List Title</label>
                            <input type="text" class="form-control" id="reading_list_title" name="reading_list_title">
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div> 
        
            <div class="book-list" style="margin-top:10px">
            <?php foreach ($readingLists as $readingList): ?>
                    <div class="row">
                        <div class="col-sm-11">
                            <h4 style="color:gray;"><?php echo htmlspecialchars($readingList['reading_list_title']); ?></h4>
                        </div>
                        <div class="col-sm-1" style="display:flex; height:23px">
                            <!-- Rename Reading List Modal -->
                            <button style="margin-right:5px;" type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#renameModal<?php echo htmlspecialchars($readingList['reading_list_id']); ?>">
                            <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="account.php" method="post">
                                <input type="hidden" name="reading_list_id_to_remove" value="<?php echo htmlspecialchars($readingList['reading_list_id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                        <!-- Modal -->
                        <div class="modal fade" id="renameModal<?php echo htmlspecialchars($readingList['reading_list_id']); ?>" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="renameModalLabel">Rename Reading List</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <form action="account.php" method="post">
                                        <div class="mb-3">
                                            <label for="reading_list_title" class="form-label">Reading List Title</label>
                                            <input type="text" class="form-control" id="reading_list_title" name="reading_list_title" value="<?php echo htmlspecialchars($readingList['reading_list_title']); ?>">
                                            <input type="hidden" name="reading_list_id" value="<?php echo htmlspecialchars($readingList['reading_list_id']); ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Rename</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="row">  
                        <!-- If empty display message saying No Books in Reading List -->
                        <?php if (empty($readingList['books'])): ?>
                            <p>There are currently no books in this reading list 😓</p>
                        <?php endif; ?>
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
                    <!-- Line -->
                    <hr>
            <?php endforeach; ?>

            </div>

            <div class="row">
                <h3><b>Books You've Read</b></h3>  
                <?php if (empty($readList)): ?>
                    <p>You Have Read No Books</p>
                <?php endif; ?>
                <?php foreach ($readList as $book): ?>
                    <div class="col-md-2">
                        <div style="text-align:center">
                        <a href="book.php?isbn13=<?php echo htmlspecialchars($book['isbn13']); ?>">
                            <img src="<?php echo htmlspecialchars($book['Thumbnail']); ?>" alt="Book Thumbnail">
                        </a>
                        <p style="text-align:center"><?php echo htmlspecialchars($book['title']); ?></p>
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
                <a href="book.php?isbn13=<?php echo htmlspecialchars($review['isbn13']); ?>">
                <img src="<?php echo htmlspecialchars($review['Thumbnail']); ?>" alt="Book Thumbnail" style="width:100px; height:150px;">
                </a>
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