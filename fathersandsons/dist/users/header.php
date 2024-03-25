<?php
session_start();

// Flag to determine if the user is logged in
$userLoggedIn = isset($_SESSION["user"]);

// Function to redirect to login if the user is not logged in
function requireLogin() {
    global $userLoggedIn;
    if (!$userLoggedIn) {
        header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

// Check for read-only admin role
function isReadOnlyAdmin() {
    return isset($_SESSION["user"]) && $_SESSION["user"]["role"] === 'readOnly';
}

// Check for read/write admin role
function isReadWriteAdmin() {
    return isset($_SESSION["user"]) && $_SESSION["user"]["role"] === 'readWrite';
}

// Optional: Function to display user-specific or general messages
function userGreeting() {
    global $userLoggedIn;
    if ($userLoggedIn) {
        echo "Hello, " . htmlspecialchars($_SESSION["user"]["firstName"]) . "!";
    } else {
        echo "Welcome, Guest!";
    }
}
?>
