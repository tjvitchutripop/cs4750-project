<?php
// session_start();
//https://docs.google.com/document/d/1O5voZDdHTsqQdEx9bH-GcTt6h2XGVAGCPLXOHSPW0Vc/edit
function addRequests($reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{
   global $db;   
   $reqDate = date('Y-m-d');      // ensure proper data type before inserting it into a db
   
   // $query = "INSERT INTO requests (reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES ('2024-03-18', 'CCC', 'Someone', 'fix light', 'medium')";
   // $query = "INSERT INTO requests (reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES ('" . $reqDate . "', '" . $roomNumber . "', '" . $reqBy . "', '" . $repairDesc . "', '" . $reqPriority . "')";  
    
   $query = "INSERT INTO requests (reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES (:reqDate, :roomNumber, :reqBy, :repairDesc, :reqPriority)";  
   
   try { 
      // $statement = $db->query($query);   // compile & exe

      // prepared statement
      // pre-compile
      $statement = $db->prepare($query);

      // fill in the value
      $statement->bindValue(':reqDate', $reqDate);
      $statement->bindValue(':roomNumber', $roomNumber);
      $statement->bindValue(':reqBy',$reqBy);
      $statement->bindValue(':repairDesc', $repairDesc);
      $statement->bindValue(':reqPriority', $reqPriority);

      // exe
      $statement->execute();
      $statement->closeCursor();
   } catch (PDOException $e)
   {
      $e->getMessage();   // consider a generic message
   } catch (Exception $e) 
   {
      $e->getMessage();   // consider a generic message
   }

}

// Given that the structure of Comemnt is comment_id, user_id, content, review_id, time_posted
function addComment($userId, $content, $reviewId) {
   global $db;
   $comment_id = mt_rand(100000000, 999999999);
   $query = "INSERT INTO Comment (comment_id, user_id, content, review_id, time_posted) VALUES (:comment_id, :userId, :content, :reviewId, NOW())";
   $statement = $db->prepare($query);
   $statement->bindValue(':comment_id', $comment_id);
   $statement->bindValue(':userId', $userId);
   $statement->bindValue(':content', $content);
   $statement->bindValue(':reviewId', $reviewId);
   $statement->execute();
}

function checkAdmin($userId) {
   global $db;
   $query = "SELECT admin FROM User WHERE user_id = :userId";
   $statement = $db->prepare($query);
   $statement->bindValue(':userId', $userId);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();
   return $result['admin'];
}

// Get Comments from Review ID (return with User First Name and Last Name)

function getCommentsForReview($reviewId) {
   global $db;
   $query = "SELECT c.*, u.first_name, u.last_name FROM Comment AS c JOIN User AS u ON c.user_id = u.user_id WHERE review_id = :reviewId ORDER BY time_posted DESC";
   $statement = $db->prepare($query);
   $statement->bindValue(':reviewId', $reviewId);
   $statement->execute();
   $result = $statement->fetchAll(PDO::FETCH_ASSOC);
   $statement->closeCursor();
   return $result;
}

function getAllRequests()
{
   global $db;
   $query = "select * from Books";    
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function getBook($isbn13)  
{
   global $db;
   $query = "select * from Books where isbn13=:isbn13"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   return $result;
}

function getBookbyid($isbn13)  
{
   global $db;
   $query = "select * from Books where isbn13=:isbn13"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}
function getTitle($title)  
{
   global $db;
   $query = "select * from Books where title=:title"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':title', $title);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getBookFromAuthors($author)
{
   global $db;
   $query = "SELECT * FROM Books NATURAL JOIN Books_authors WHERE author_name=:author;"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':author', $author);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getAuthors($isbn13)
{
   global $db;
   $query = "select author_name from Books_authors where isbn13=:isbn13"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getReviews($isbn13)
{
   global $db;
   $query = "SELECT Reviews.*, User.*, Rates.number_of_stars 
             FROM Reviews 
             LEFT JOIN Rates ON Reviews.user_id = Rates.user_id AND Reviews.isbn13 = Rates.isbn13 
             JOIN User ON Reviews.user_id = User.user_id
             WHERE Reviews.isbn13 = :isbn13 
             ORDER BY Reviews.time_posted DESC"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getBookReadByUser($user_id)
{
   global $db;
   $query = "SELECT * From `Reads` NATURAL JOIN Books WHERE user_id = :user_id;"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':user_id', $user_id);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

// {
//    global $db;
//    $query = "select * from Books";    
//    $statement = $db->prepare($query);    // compile
//    $statement->execute();
//    $result = $statement->fetchAll();     // fetch()
//    $statement->closeCursor();

//    return $result;
// }

function getRequestById($id)  
{
   global $db;
   $query = "select * from requests where reqId=:reqId"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':reqId', $id);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   return $result;
}

function updateRequest($reqId, $reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{
   global $db;
   $query = "update requests set reqDate=:reqDate, roomNumber=:roomNumber, 
   reqBy=:reqBy, repairDesc=:repairDesc, reqPriority=:reqPriority where reqId=:reqId" ; 

   $statement = $db->prepare($query);
   $statement->bindValue(':reqId', $reqId);
   $statement->bindValue(':reqDate', $reqDate);
   $statement->bindValue(':roomNumber', $roomNumber);
   $statement->bindValue(':reqBy',$reqBy);
   $statement->bindValue(':repairDesc', $repairDesc);
   $statement->bindValue(':reqPriority', $reqPriority);

   $statement->execute();
   $statement->closeCursor();



}

function deleteComment($comment_id)
{
   global $db;
   $query = "delete from Comment where comment_id=:comment_id"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':comment_id', $comment_id);
   $statement->execute();
   $statement->closeCursor();
}

function deleteRequest($reqId)
{

    
}

function addReadThisBook($user_id, $isbn13) {
   global $db;
   $query = "INSERT INTO `Reads` (user_id, isbn13) VALUES (:user_id, :isbn13);";
   try {
      $statement = $db->prepare($query);
      $statement->bindValue(':user_id', $user_id);
      $statement->bindValue(':isbn13', $isbn13);
      $statement->execute();
      $statement->closeCursor();

   } catch (PDOException $e) {
      $error_message = $e->getMessage();
      echo "<p>Error inserting user into database: $error_message </p>";
   }
}

function getBookReads($isbn13)
{
   global $db;
   $query = "SELECT COUNT(user_id) As num From `Reads` WHERE isbn13 =:isbn13;";
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   return $result;
}

function addLike($review_id) {
   global $db;
   $query = "UPDATE Reviews SET likes = likes + 1 WHERE review_id =:review_id;";

   $statement = $db->prepare($query); 
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();

   return $statement->rowCount() > 0;
}

function getNumLikes($review_id) {
   global $db;
   $query = "SELECT COUNT(likes) FROM Likes WHERE review_id =:review_id;";

   $statement = $db->prepare($query); 
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();

   return $result;
}

function removeBook($isbn13) {
   global $db;
   // remove book from Books authors
   $queryBooksAuthors = "DELETE FROM Books_authors WHERE isbn13 = :isbn13";
   $statementBooksAuthors = $db->prepare($queryBooksAuthors);
   $statementBooksAuthors->bindValue(':isbn13', $isbn13);
   $statementBooksAuthors->execute();
   $statementBooksAuthors->closeCursor();
   // remove comments on reviews for the book
   $queryComments = "DELETE FROM Comment WHERE review_id IN (SELECT review_id FROM Reviews WHERE isbn13 = :isbn13)";
   $statementComments = $db->prepare($queryComments);
   $statementComments->bindValue(':isbn13', $isbn13);
   $statementComments->execute();
   $statementComments->closeCursor();
   // remove likes for reviews of the book
   $queryLikes = "DELETE FROM Likes WHERE review_id IN (SELECT review_id FROM Reviews WHERE isbn13 = :isbn13)";
   $statementLikes = $db->prepare($queryLikes);
   $statementLikes->bindValue(':isbn13', $isbn13);
   $statementLikes->execute();
   $statementLikes->closeCursor();
   // remove rates for the book
   $queryRates = "DELETE FROM Rates WHERE isbn13 = :isbn13";
   $statementRates = $db->prepare($queryRates);
   $statementRates->bindValue(':isbn13', $isbn13);
   $statementRates->execute();
   $statementRates->closeCursor();
   // remove reviews for the book on provides
   $queryProvides = "DELETE FROM Provides NATURAL JOIN Reviews WHERE isbn13 = :isbn13";
   $statementProvides = $db->prepare($queryProvides);
   $statementProvides->bindValue(':isbn13', $isbn13);
   $statementProvides->execute();
   $statementProvides->closeCursor();
   // remove reviews for the book
   $queryReviews = "DELETE FROM Reviews WHERE isbn13 = :isbn13";
   $statementReviews = $db->prepare($queryReviews);
   $statementReviews->bindValue(':isbn13', $isbn13);
   $statementReviews->execute();
   $statementReviews->closeCursor();
   // remove book from Reads
   $queryReads = "DELETE FROM Reads WHERE isbn13 = :isbn13";
   $statementReads = $db->prepare($queryReads);
   $statementReads->bindValue(':isbn13', $isbn13);
   $statementReads->execute();
   $statementReads->closeCursor();
   // remove book from Reading_list_isbn13
   $queryReadingListIsbn13 = "DELETE FROM Reading_list_isbn13 WHERE isbn13 = :isbn13";
   $statementReadingListIsbn13 = $db->prepare($queryReadingListIsbn13);
   $statementReadingListIsbn13->bindValue(':isbn13', $isbn13);
   $statementReadingListIsbn13->execute();
   $statementReadingListIsbn13->closeCursor();
   // remove book from Books
   $queryBooks = "DELETE FROM Books WHERE isbn13 = :isbn13";
   $statementBooks = $db->prepare($queryBooks);
   $statementBooks->bindValue(':isbn13', $isbn13);
   $statementBooks->execute();
   $statementBooks->closeCursor();
}

function addUser($firstName, $lastName, $userId, $password, $admin) {
   global $db;
   $query = "INSERT INTO User (user_id, user_password, profile_picture, first_name, last_name, admin)
   VALUES (:userId, :password, 'profile_ex.jpg', :firstName, :lastName, :admin)";  
   
   try {
      // $db->beginTransaction();
      $statement = $db->prepare($query);

      $statement->bindValue(':userId', $userId, PDO::PARAM_INT); 
      // $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT)); 
      $statement->bindValue(':password', $password);
      $statement->bindValue(':firstName', $firstName);
      $statement->bindValue(':lastName', $lastName);
      $statement->bindValue(':admin', $admin);

      $statement->execute();
      $statement->closeCursor();
      return true;
   } catch (PDOException $e) {
      // $db->rollback(); // Rollback transaction on failure
      $error_message = $e->getMessage();
      echo "<p>Error inserting user into database: $error_message </p>";
      return false; // Return false to indicate failure
      // $error_message = $e->getMessage();
      // echo "<p>Error inserting user into database: $error_message </p>";
   }
}

function validateUser($userId, $password) {
   global $db;
   $query = "SELECT user_password FROM User WHERE user_id = :userId";
   
   try {
       $statement = $db->prepare($query);
       $userId = (int)$userId;
       $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
       $statement->execute();
       
       $row = $statement->fetch();
       $statement->closeCursor();
       
       if ($row) {
           if ($password === $row['user_password']) { 

               return true;
           } else {
               error_log("Password mismatch for user ID: {$userId}");
               return false;
           }
       } else {
           error_log("No user found with ID: {$userId}");
           return false;
       }
   } catch (PDOException $e) {
       error_log("PDOException in validateUser(): " . $e->getMessage());
       return false;
   }
}

function addBook($isbn13, $isbn10, $title, $subtitle, $Thumbnail, $Description, $Number_of_pages, $Categories, $Average_rating, $Rating_count){
   global $db;
   $query = "INSERT INTO Books (isbn13, Thumbnail, Description, isbn10, subtitle, Average_rating, Number_of_pages, Rating_count, title, Categories)
   VALUES (:isbn13, :Thumbnail, :Description, :isbn10, :subtitle, :Average_rating, :Number_of_pages, :Rating_count, :title, :Categories)";   
   try {
      // $db->beginTransaction();
      $statement = $db->prepare($query);

      $statement->bindValue(':isbn13', $isbn13, PDO::PARAM_INT); 
      $statement->bindValue(':isbn10', $isbn10, PDO::PARAM_INT); 
      $statement->bindValue(':title', $title);
      $statement->bindValue(':subtitle', $subtitle);
      $statement->bindValue(':Thumbnail', $Thumbnail);
      $statement->bindValue(':Description', $Description);
      $statement->bindValue(':Number_of_pages', $Number_of_pages, PDO::PARAM_INT); 
      $statement->bindValue(':Categories', $Categories);
      $statement->bindValue(':Average_rating', $Average_rating, PDO::PARAM_INT); 
      $statement->bindValue(':Rating_count', $Rating_count, PDO::PARAM_INT); 

      $statement->execute();
      $statement->closeCursor();
      return true;
   } catch (PDOException $e) {
      // $db->rollback(); // Rollback transaction on failure
      $error_message = $e->getMessage();
      echo "<p>Error inserting user into database: $error_message </p>";
      return false; // Return false to indicate failure
      // $error_message = $e->getMessage();
      // echo "<p>Error inserting user into database: $error_message </p>";
   }
}


function getReadingLists($user_id) {
   global $db;
   $query = "SELECT b.*, rl.reading_list_id, rl.reading_list_title
             FROM Reading_list AS rl
             LEFT JOIN Reading_list_isbn13 AS rli ON rl.reading_list_id = rli.reading_list_id
             LEFT JOIN Books AS b ON rli.isbn13 = b.isbn13
             JOIN Creates AS c ON rl.reading_list_id = c.reading_list_id 
             WHERE c.user_id = :user_id"; 

   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
   $statement->execute();
   $result = $statement->fetchAll(PDO::FETCH_ASSOC);
   $statement->closeCursor();

   // Organize books by reading list
   $readingLists = array();
   foreach ($result as $book) {
       $readingListId = $book['reading_list_id'];
       $readingListTitle = $book['reading_list_title'];
       if (!isset($readingLists[$readingListId])) {
           $readingLists[$readingListId] = array(
               'reading_list_id' => $readingListId,
               'reading_list_title' => $readingListTitle,
               'books' => array()
           );
       }
       // Only add the book if it exists (i.e., it's not NULL)
       if ($book['isbn13'] !== null) {
           $readingLists[$readingListId]['books'][] = $book;
       }
   }

   return $readingLists;
}

function renameReadingList($user_id, $reading_list_id, $new_title) {
   global $db;
   $query = "UPDATE Reading_list SET reading_list_title = :new_title WHERE reading_list_id = :reading_list_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':new_title', $new_title);
   $statement->bindValue(':reading_list_id', $reading_list_id);
   $statement->execute();
   $statement->closeCursor();
}

function deleteReadingList($user_id, $reading_list_id) {
   global $db;
   // delete from Creates first to avoid foreign key constraint violation
   $queryCreates = "DELETE FROM Creates WHERE user_id = :user_id AND reading_list_id = :reading_list_id";
   $statementCreates = $db->prepare($queryCreates);
   $statementCreates->bindValue(':user_id', $user_id);
   $statementCreates->bindValue(':reading_list_id', $reading_list_id);
   $statementCreates->execute();
   $statementCreates->closeCursor();

   // delete from Reading_list_isbn13
   $queryReadingListIsbn13 = "DELETE FROM Reading_list_isbn13 WHERE reading_list_id = :reading_list_id";
   $statementReadingListIsbn13 = $db->prepare($queryReadingListIsbn13);
   $statementReadingListIsbn13->bindValue(':reading_list_id', $reading_list_id);
   $statementReadingListIsbn13->execute();
   $statementReadingListIsbn13->closeCursor();

   // delete from Reading_list
   $queryReadingList = "DELETE FROM Reading_list WHERE reading_list_id = :reading_list_id";
   $statementReadingList = $db->prepare($queryReadingList);
   $statementReadingList->bindValue(':reading_list_id', $reading_list_id);
   $statementReadingList->execute();
   $statementReadingList->closeCursor();
}


function getReadingListID_Title($user_id) {
   global $db;
   $query = "SELECT reading_list_id, reading_list_title FROM Creates NATURAL JOIN Reading_list WHERE user_id = :user_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
   $statement->execute();
   $result = $statement->fetchAll(PDO::FETCH_ASSOC);
   $statement->closeCursor();

   return $result;
}

function getUserName($user_id) {
   global $db;
   $query = "SELECT first_name, last_name FROM User WHERE user_id = :user_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
   $statement->execute();
   $result = $statement->fetch(PDO::FETCH_ASSOC);
   $statement->closeCursor();

   return $result;
}

function getUserReviews($user_id) {
   global $db;
   $query = "SELECT *
             FROM Reviews 
             LEFT JOIN Rates ON Reviews.user_id = Rates.user_id AND Reviews.isbn13 = Rates.isbn13 
             LEFT JOIN User ON Reviews.user_id = User.user_id
             LEFT JOIN Books ON Reviews.isbn13 = Books.isbn13
             WHERE Reviews.user_id = :user_id
             ORDER BY Reviews.time_posted DESC"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':user_id', $user_id);
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}



function isBookRead($user_id, $isbn13) {
   global $db;
   $query = "SELECT * FROM `Reads` WHERE user_id = :user_id AND isbn13 = :isbn13";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id);
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();
   
   debug_to_console($result ? "TRUE" : "FALSE");
   return $result ? true : false;
}


function addToReadingList($user_id, $isbn13, $reading_list_id) {
   global $db;

   $queryCheck = "SELECT reading_list_id FROM Creates WHERE user_id = :user_id AND reading_list_id = :reading_list_id";
   $statementCheck = $db->prepare($queryCheck);
   $statementCheck->bindValue(':user_id', $user_id);
   $statementCheck->bindValue(':reading_list_id', $reading_list_id);
   $statementCheck->execute();
   $readingList = $statementCheck->fetch();
   $statementCheck->closeCursor();

   // if (!$readingList) {

   //     $queryInsertReadingList = "INSERT INTO Reading_list (reading_list_id, reading_list_title) VALUES (:user_id, 'Default Reading List')";
   //     $statementInsertReadingList = $db->prepare($queryInsertReadingList);
   //     $statementInsertReadingList->bindValue(':user_id', $user_id);
   //     $statementInsertReadingList->execute();
   //     $statementInsertReadingList->closeCursor();

   //     $queryCreate = "INSERT INTO Creates (user_id, reading_list_id) VALUES (:user_id, :user_id)";
   //     $statementCreate = $db->prepare($queryCreate);
   //     $statementCreate->bindValue(':user_id', $user_id);
   //     $statementCreate->execute();
   //     $statementCreate->closeCursor();
   //     $readingList['reading_list_id'] = $user_id;
   // }

   // Add the book to the user's reading list
   $queryInsert = "INSERT INTO Reading_list_isbn13 (reading_list_id, isbn13) VALUES (:reading_list_id, :isbn13) ON DUPLICATE KEY UPDATE isbn13=isbn13";
   $statementInsert = $db->prepare($queryInsert);
   $statementInsert->bindValue(':reading_list_id', $readingList['reading_list_id']);
   $statementInsert->bindValue(':isbn13', $isbn13);
   $statementInsert->execute();
   $statementInsert->closeCursor();
}

function createReadingList($user_id, $reading_list_title) {
    global $db;
   // generate randome 8 digit number for reading list id
   $reading_list_id = mt_rand(10000000, 99999999);

   $query = "INSERT INTO Reading_list (reading_list_id, reading_list_title) VALUES (:reading_list_id, :reading_list_title)";
   $statement = $db->prepare($query);
   $statement->bindValue(':reading_list_id', $reading_list_id);
   $statement->bindValue(':reading_list_title', $reading_list_title);
   $statement->execute();
   $statement->closeCursor();


    $queryCreates = "INSERT INTO Creates (user_id, reading_list_id) VALUES (:user_id, :reading_list_id)";
    $statementCreates = $db->prepare($queryCreates);
    $statementCreates->bindValue(':user_id', $user_id);
    $statementCreates->bindValue(':reading_list_id', $reading_list_id);
    $statementCreates->execute();
    $statementCreates->closeCursor();
}

function removeFromReadingList($user_id, $isbn13, $reading_list_id) {
    global $db;
    $query = "DELETE FROM Reading_list_isbn13 WHERE reading_list_id = :reading_list_id AND isbn13 = :isbn13";

    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':reading_list_id', $reading_list_id);
        $statement->bindValue(':isbn13', $isbn13);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        error_log("Error removing book from reading list: $error_message");
    }
}

function removeReadThisBook($user_id, $isbn13) {
   global $db;
   $query = "DELETE FROM `Reads` WHERE user_id = :user_id AND isbn13 = :isbn13";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id);
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $statement->closeCursor();
}

function addReview($userId, $isbn13, $rating, $reviewContent) {
   global $db;
   
   $db->beginTransaction();
   
   try {
       $review_id = mt_rand(100000, 999999);

       // Insert review
       if($reviewContent != ""){
         $queryReview = "INSERT INTO Reviews (review_id, isbn13, user_id, likes, content, time_posted) VALUES (:review_id, :isbn13, :userId, :likes, :content, NOW())";
         $statementReview = $db->prepare($queryReview);
         $statementReview->bindValue(':review_id', $review_id);
         $statementReview->bindValue(':isbn13', $isbn13);
         $statementReview->bindValue(':userId', $userId);
         $statementReview->bindValue(':likes', 0); 
         $statementReview->bindValue(':content', $reviewContent);
         $statementReview->execute();
         $statementReview->closeCursor();
       }

       // Insert rating
       if ($rating) {
           $queryRating = "INSERT INTO Rates (user_id, isbn13, number_of_stars) VALUES (:userId, :isbn13, :rating) ON DUPLICATE KEY UPDATE number_of_stars = :rating";
           $statementRating = $db->prepare($queryRating);
           $statementRating->bindValue(':userId', $userId);
           $statementRating->bindValue(':isbn13', $isbn13);
           $statementRating->bindValue(':rating', $rating);
           $statementRating->execute();
           $statementRating->closeCursor();
       }
       $db->commit();
   } catch (PDOException $e) {
       $db->rollback();
       $error_message = $e->getMessage();
       error_log("Error posting review: $error_message");
       throw $e;
   }
}

function getUserRating($userId, $isbn13) {
   global $db;
   $query = "SELECT number_of_stars FROM Rates WHERE user_id = :userId AND isbn13 = :isbn13";
   $statement = $db->prepare($query);
   $statement->bindValue(':userId', $userId);
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();
   return $result ? $result['number_of_stars'] : null;
}

function addLikeToReview($user_id, $review_id) {
   global $db;
   $query = "INSERT INTO `Likes` (user_id, review_id) VALUES (:user_id, :review_id)";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id);
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();
   $statement->closeCursor();
}

function getNumberLikes($review_id) {
   global $db;
   $query = "SELECT COUNT(user_id) FROM `Likes` WHERE review_id = :review_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();
   return $result ? $result['COUNT(user_id)'] : 0;
}

function removeLikeFromReview($user_id, $review_id) {
   global $db;
   $query = "DELETE FROM `Likes` WHERE user_id = :user_id AND review_id = :review_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id);
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();
   $statement->closeCursor();
}

function hasLikedReview($user_id, $review_id) {
   global $db;
   $query = "SELECT * FROM `Likes` WHERE user_id = :user_id AND review_id = :review_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id);
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();
   $result = $statement->fetch();
   $statement->closeCursor();
   return $result ? true : false;
}

function calculateAverageRating($isbn13) {
   global $db;
   
   // Get average rating and number of people rating from Rates table
   $query = "SELECT AVG(number_of_stars) AS average_rating, COUNT(user_id) AS rating_count FROM Rates WHERE isbn13 = :isbn13";
   $statement = $db->prepare($query);
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $result = $statement->fetch(PDO::FETCH_ASSOC);
   $statement->closeCursor();
   
   // Get average rating and number of people that have already rated from Books table
   $query = "SELECT Average_rating, Rating_count FROM Books WHERE isbn13 = :isbn13";
   $statement = $db->prepare($query);
   $statement->bindValue(':isbn13', $isbn13);
   $statement->execute();
   $book = $statement->fetch(PDO::FETCH_ASSOC);
   $statement->closeCursor();
   
   // Combine the ratings and calculate the weighted average
   $average_rating = (($result['average_rating'] * $result['rating_count']) + ($book['Average_rating'] * $book['Rating_count'])) / ($result['rating_count'] + $book['Rating_count']);
   // round two decimal places
   $average_rating = round($average_rating, 2);
   return $average_rating;
}


function removeReview($user_id, $review_id) {
   global $db;
   $query = "DELETE FROM Reviews WHERE user_id = :user_id AND review_id = :review_id";
   
   try {
       $statement = $db->prepare($query);
       $statement->bindValue(':user_id', $user_id);
       $statement->bindValue(':review_id', $review_id);
       $statement->execute();
       $statement->closeCursor();
   } catch (PDOException $e) {
       $error_message = $e->getMessage();
       error_log("Error removing review: $error_message");
   }
}

function getReviewById($review_id) {
   global $db;
   $query = "SELECT * FROM Reviews WHERE review_id = :review_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':review_id', $review_id);
   $statement->execute();
   $review = $statement->fetch(PDO::FETCH_ASSOC);
   $statement->closeCursor();
   return $review;
}

function updateReview($review_id, $userId, $rating, $reviewContent) {
   global $db;
   $db->beginTransaction();
   try {
       // Update the review 
       $queryReview = "UPDATE Reviews SET content = :content WHERE review_id = :review_id AND user_id = :userId";
       $statementReview = $db->prepare($queryReview);
       $statementReview->bindValue(':content', $reviewContent);
       $statementReview->bindValue(':review_id', $review_id);
       $statementReview->bindValue(':userId', $userId);
       $statementReview->execute();
       $statementReview->closeCursor();

       if ($rating) {
           $queryRating = "UPDATE Rates SET number_of_stars = :rating WHERE user_id = :userId AND isbn13 = (SELECT isbn13 FROM Reviews WHERE review_id = :review_id)";
           $statementRating = $db->prepare($queryRating);
           $statementRating->bindValue(':rating', $rating);
           $statementRating->bindValue(':userId', $userId);
           $statementRating->bindValue(':review_id', $review_id);
           $statementRating->execute();
           $statementRating->closeCursor();
       }

       $db->commit();
   } catch (PDOException $e) {
       $db->rollback();
       $error_message = $e->getMessage();
       error_log("Error updating review: $error_message");
       throw $e;
   }
}


?>