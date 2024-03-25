<?php
// Include your database connection script
include './users/db.php';

// Retrieve user inputs
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];

// Password encryption for security
$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL statement to prevent SQL injection
$sql = "INSERT INTO user_list (FirstName, LastName, Email, Password) VALUES (:firstName, :lastName, :email, :password)";

$statement = $connection->prepare($sql);

try {
    // Bind parameters
    $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $statement->bindParam(':lastName', $lastName, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':password', $encryptedPassword, PDO::PARAM_STR);

    // Execute the statement
    $statement->execute();

    echo "New record created successfully";
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>
