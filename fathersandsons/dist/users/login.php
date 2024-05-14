<?php
session_start(); // Start a new session or resume the existing one

include 'db.php'; // Include your database connection script

// Retrieve user inputs
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL statement to prevent SQL injection
$sql = "SELECT * FROM user_list WHERE Email = :email";
$statement = $connection->prepare($sql);
$statement->bindParam(':email', $email, PDO::PARAM_STR);
$statement->execute();

$user = $statement->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Verify the password against the hash stored in the database
    if (password_verify($password, $user['Password'])) {
        // Password is correct, set session variables
        $_SESSION['userID'] = $user['UserID'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['role'] = $user['Role']; // Assuming you have a Role column
        
        // Redirect to a logged-in page
        header('Location: ../index.php');
        exit;
    } else {
        // Password is not correct
        echo "The password you entered was not valid.";
    }
} else {
    // No user found with the provided email
    echo "No account found with that email address.";
}
?>
