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
   $query = "select * from Reviews natural join User where isbn13=:isbn13"; 
   $statement = $db->prepare($query);    // compile
   $statement->bindValue(':isbn13', $isbn13);
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

function deleteRequest($reqId)
{

    
}

function addUser($firstName, $lastName, $userId, $password) {
   global $db;
   $query = "INSERT INTO User (user_id, user_password, profile_picture, first_name, last_name) 
   VALUES (:userId, :password, 'profile_ex.jpg', :firstName, :lastName)";  
   
   try {
      $statement = $db->prepare($query);

      $statement->bindValue(':userId', $userId, PDO::PARAM_INT); 
      // $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT)); 
      $statement->bindValue(':password', $password);
      $statement->bindValue(':firstName', $firstName);
      $statement->bindValue(':lastName', $lastName);

      $statement->execute();
      $statement->closeCursor();
   } catch (PDOException $e) {
      $error_message = $e->getMessage();
      echo "<p>Error inserting user into database: $error_message </p>";
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
function getReadingList($user_id) {
   global $db;
   $query = "SELECT b.* 
             FROM Books AS b 
             JOIN Reading_list_isbn13 AS rli ON b.isbn13 = rli.isbn13 
             JOIN Reading_list AS rl ON rli.reading_list_id = rl.reading_list_id 
             JOIN Creates AS c ON rl.reading_list_id = c.reading_list_id 
             WHERE c.user_id = :user_id"; 
   
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
   $statement->execute();
   $result = $statement->fetchAll(PDO::FETCH_ASSOC);
   $statement->closeCursor();

   return $result;
}

function getUserReviews($user_id) {
   global $db;
   // Including user profile picture in the selection.
   $query = "SELECT r.*, b.Thumbnail, ra.number_of_stars, u.profile_picture
             FROM Reviews AS r
             JOIN Books AS b ON r.isbn13 = b.isbn13
             JOIN User AS u ON r.user_id = u.user_id
             LEFT JOIN Rates AS ra ON ra.isbn13 = b.isbn13 AND ra.user_id = r.user_id
             WHERE r.user_id = :user_id
             ORDER BY r.time_posted DESC"; // Ordering by most recent reviews first.
   
   $statement = $db->prepare($query);
   $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
   $statement->execute();
   $result = $statement->fetchAll(PDO::FETCH_ASSOC);
   $statement->closeCursor();

   return $result;
}


function addToReadingList($user_id, $isbn13) {
   global $db;

   $queryCheck = "SELECT reading_list_id FROM Creates WHERE user_id = :user_id LIMIT 1";
   $statementCheck = $db->prepare($queryCheck);
   $statementCheck->bindValue(':user_id', $user_id);
   $statementCheck->execute();
   $readingList = $statementCheck->fetch();
   $statementCheck->closeCursor();

   if (!$readingList) {

       $queryInsertReadingList = "INSERT INTO Reading_list (reading_list_id, reading_list_title) VALUES (:user_id, 'Default Reading List')";
       $statementInsertReadingList = $db->prepare($queryInsertReadingList);
       $statementInsertReadingList->bindValue(':user_id', $user_id);
       $statementInsertReadingList->execute();
       $statementInsertReadingList->closeCursor();

       $queryCreate = "INSERT INTO Creates (user_id, reading_list_id) VALUES (:user_id, :user_id)";
       $statementCreate = $db->prepare($queryCreate);
       $statementCreate->bindValue(':user_id', $user_id);
       $statementCreate->execute();
       $statementCreate->closeCursor();
       $readingList['reading_list_id'] = $user_id;
   }

   // Add the book to the user's reading list
   $queryInsert = "INSERT INTO Reading_list_isbn13 (reading_list_id, isbn13) VALUES (:reading_list_id, :isbn13) ON DUPLICATE KEY UPDATE isbn13=isbn13";
   $statementInsert = $db->prepare($queryInsert);
   $statementInsert->bindValue(':reading_list_id', $readingList['reading_list_id']);
   $statementInsert->bindValue(':isbn13', $isbn13);
   $statementInsert->execute();
   $statementInsert->closeCursor();
}

function removeFromReadingList($user_id, $isbn13) {
    global $db;
    $query = "DELETE FROM Reading_list_isbn13 WHERE reading_list_id = :reading_list_id AND isbn13 = :isbn13";

    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':reading_list_id', $user_id);
        $statement->bindValue(':isbn13', $isbn13);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        error_log("Error removing book from reading list: $error_message");
    }
}

function addReview($userId, $isbn13, $rating, $reviewContent) {
   global $db;
   
   $db->beginTransaction();
   
   try {
       $review_id = mt_rand(100000, 999999);

       // Insert review
       $queryReview = "INSERT INTO Reviews (review_id, isbn13, user_id, likes, content, time_posted) VALUES (:review_id, :isbn13, :userId, :likes, :content, NOW())";
       $statementReview = $db->prepare($queryReview);
       $statementReview->bindValue(':review_id', $review_id);
       $statementReview->bindValue(':isbn13', $isbn13);
       $statementReview->bindValue(':userId', $userId);
       $statementReview->bindValue(':likes', 55); 
       $statementReview->bindValue(':content', $reviewContent);
       $statementReview->execute();
       $statementReview->closeCursor();

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



?>