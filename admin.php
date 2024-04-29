<!-- Page where admins only can access and can add/remove/edit books -->
<?php
session_start(); 

include('request-db.php'); 
include('connect-db.php');

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['isbn13_to_remove'])) {
    removeBook($_POST['isbn13_to_remove']);
    header("Location: admin.php");
    exit();
}
if (isset($_POST['isbn13']) && isset($_POST['isbn10']) && isset($_POST['title']) && isset($_POST['subtitle']) && isset($_POST['Thumbnail']) && isset($_POST['Description']) && isset($_POST['Number_of_pages']) && isset($_POST['Categories']) && isset($_POST['Average_rating']) && isset($_POST['Rating_count'])){
    $isbn13 = $_POST['isbn13'];
    $isbn10 = $_POST['isbn10'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $Thumbnail = $_POST['Thumbnail'];
    $Description = $_POST['Description'];
    $Number_of_pages = $_POST['Number_of_pages'];
    $Categories = $_POST['Categories'];
    $Average_rating = $_POST['Average_rating'];
    $Rating_count = $_POST['Rating_count'];
    addBook($isbn13, $isbn10, $title, $subtitle, $Thumbnail, $Description, $Number_of_pages, $Categories, $Average_rating, $Rating_count);
    header("Location: admin.php");
    exit();
}


$list_of_requests = getAllRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Literary Loop | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<?php include("header.php"); ?>
<?php if (checkAdmin($_SESSION['userId'])) : ?> 
<main class="container">
    <div style="display:flex; justify-content:space-between;">
        <h1>Admin Dashboard 📂</h1>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add a New Book
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add a New Book</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin.php" method="post">
                        <div class="mb-3">
                        <!-- Given Books(isbn13, Thumbnail, Description, isbn10, subtitle, Average_rating, Number_of_pages, Rating_count, title, Categories)  -->
                        <label for="isbn13" class="form-label">ISBN13</label>
                        <input type="number" class="form-control" id="isbn13" name="isbn13" required>
                        <label for="isbn10" class="form-label">ISBN10</label>
                        <input type="number" class="form-control" id="isbn10" name="isbn10" required>
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" required>
                        <label for="Thumbnail" class="form-label">Thumbnail</label>
                        <input type="text" class="form-control" id="Thumbnail" name="Thumbnail" required>
                        <label for="Description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" required>
                        <label for="Number_of_pages" class="form-label
                        ">Number of Pages</label>
                        <input type="number" class="form-control" id="Number_of_pages" name="Number_of_pages" required>
                        <label for="Categories" class="form-label">Categories</label>
                        <input type="text" class="form-control" id="Categories" name="Categories" required>
                        <label for="Average_rating" class="form-label">Average Rating</label>
                        <input value=0 type="number" class="form-control" id="Average_rating" name="Average_rating" required>
                        <label for="Rating_count" class="form-label">Rating Count</label>
                        <input value=0 type="number" class="form-control" id="Rating_count" name="Rating_count" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
    </div>
    <div class="row justify-content-center">  
    <?php foreach ($list_of_requests as $req_info): ?>
      <div class="col-md-2">
        <div class="book">
          <a href="book.php?isbn13=<?php echo urlencode($req_info['isbn13']); ?>" style="text-decoration: none; color: inherit;">
            <?php if (!empty($req_info['Thumbnail'])): ?>
              <img src="<?php echo htmlspecialchars($req_info['Thumbnail']); ?>" alt="Thumbnail">
            <?php else: ?>
              <img src="no-thumbnail.jpg" alt="No Thumbnail Available">
             <?php endif; ?>
            <p class="title"><?php echo htmlspecialchars($req_info['title']); ?></p>
            <!-- Delete Book -->
            <form action="admin.php" method="post">
                <input type="hidden" name="isbn13_to_remove" value="<?php echo htmlspecialchars($req_info['isbn13']); ?>">
                <button type="submit" class="btn btn-danger">Remove Book <i class="bi bi-x-circle"></i></button>
            </form>
          </a>
        </div>
      </div>
    <?php endforeach; ?>  
    </div>
</main>
<style>
.container {
    margin-top: 80px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.book {
    text-align: center;
    margin: 10px;
}

.book img {
    width: 150px; /* Adjust size as needed */
    height: 200px; /* Adjust size as needed */
    object-fit: cover; /* Ensure the image fills the designated space */
}

.title {
    margin-top: 10px;
    font-weight: bold;
}
</style>
<?php else : ?>
    <div style="margin: 80px;">
        <h1>Admin Dashboard 🛑</h1>
        <p>You do not have permission to access this page.  If you feel that you should have access, please check that you are signing into your admin account.</p>
    </div>
<?php endif; ?>