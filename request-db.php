<?php
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

{
   global $db;
   $query = "select * from Books";    
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

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





?>