<?php
include 'db.php'; // Your database connection file

// Assuming you've already retrieved and sanitized the form inputs into $firstName, $lastName, $email, $password

$encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO user_list (FirstName, LastName, Email, Password) VALUES (:firstName, :lastName, :email, :password)";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $encryptedPassword);
    $stmt->execute();
    echo "Success!";
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        echo "There is already an account with that e-mail.";
    } else {
        echo "Error: Something went wrong.";
        // Log the error or send it to an administrator
        // error_log($e->getMessage());
    }
}
?>

