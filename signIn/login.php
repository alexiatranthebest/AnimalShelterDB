<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['user'] ?? '';
    $password = $_POST['pass'] ?? '';
    
    // make sure that the inputs aren't empty
    if (empty($userID) || empty($password)) {
        header("Location: login.html?error=empty");
        exit();
    }
    
    // userID needs to be only ints
    if (!is_numeric($userID)) {
        header("Location: login.html?error=invalid");
        exit();
    }
    
    // prevent SQL injection
    $stmt = $conn->prepare("SELECT UserID, password, userRole, firstName, lastName, email FROM User WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();


        
        // check if password matches userID in database
        if ($password === $user['password']) {              // login was successful
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['user_role'] = $user['userRole'];
            $_SESSION['first_name'] = $user['firstName'];
            $_SESSION['last_name'] = $user['lastName'];
            $_SESSION['email'] = $user['email'];
            
            // go to dashboard depending on user role
            if ($user['userRole'] === 'Employee') {
                $stmt = $conn-> prepare("SELECT M_userID FROM manager WHERE M_userID = ?");
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $ismanager = $stmt->get_result();
                if ($ismanager->num_rows === 1) {
                    header("Location: ../managerView/managerView.php");
                    }
                else{
                    header("Location: ../employeeView/employeeView.php");
                }
            }
            elseif ($user['userRole'] === 'Manager') {
                
            } 
            else {
                header("Location: ../adopterView/adopterView.php");
            }
            exit();
        } 
        else {
            header("Location: login.html?error=invalid");
            exit();
        }
    } 
    else {
        header("Location: login.html?error=notfound");
        exit();
    }

    
    $stmt->close();
}

$conn->close();
?>