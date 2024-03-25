<?php
    session_start();

    // Error reporting for development
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include('db.php');

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        try {
            $query = $connection->prepare("SELECT * FROM accounts WHERE email=:email");
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->execute();
            
            $result = $query->fetch(PDO::FETCH_ASSOC);  
            
            if (!$result) {
                echo '<p class="error">Username or password is incorrect!</p>';
            } else {
                if (password_verify($password, $result['password'])) {
                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);
                    
                    $_SESSION['user'] = $result['email'];
                    
                    // Fetching additional information now that the user is verified
                    $accountId = $result['id'];
                    
                    $teacherQuery = $connection->prepare("SELECT teacher_id, school_id, is_admin FROM Teachers WHERE account_id = :accountId");
                    $teacherQuery->bindParam("accountId", $accountId, PDO::PARAM_INT);
                    $teacherQuery->execute();
                    
                    $teacherResult = $teacherQuery->fetch(PDO::FETCH_ASSOC);
                    
                    if ($teacherResult) {
                        $_SESSION['teacher_id'] = $teacherResult['teacher_id'];
                        $_SESSION['school_id'] = $teacherResult['school_id'];
                        $_SESSION['is_admin'] = $teacherResult['is_admin'] == 1; // Assuming 'is_admin' is the column name
                    } else {
                        echo '<p class="error">No teacher ID associated with this account.</p>';
                        exit();
                    }               
                    
                    // Redirect to the desired page after successful login
                    header("Location: ../home.php");
                    exit(); 
                } else {
                    echo '<p class="error">Username or password is incorrect!</p>';
                }
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage(); // Show the exception error message
        }
    }
?>
