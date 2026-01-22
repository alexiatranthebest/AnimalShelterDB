<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['fname'] ?? '');
    $lastName = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phonenum'] ?? '');
    $address = trim($_POST['streetaddress'] ?? '');
    $dob = $_POST['date'] ?? '';
    $password = $_POST['pass'] ?? '';
    $confirmPassword = $_POST['confirmpass'] ?? '';

    // make sure all fields are filled up --> if not --> exit
    if (empty($firstName) || empty($lastName) || empty($email) ||
        empty($phone) || empty($address) || empty($dob) || 
        empty($password) || empty($confirmPassword)) {
        header("Location: signup.html?error=empty");
        exit();
    }

    // passwords match or password length is not < 6
    if ($password !== $confirmPassword) {
        header("Location: signup.html?error=password_mismatch");
        exit();
    }
    if (strlen($password) < 6){
        header("Location: signup.html?error=password_short");
        exit();
    }

    // check phone format
    if (!preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $phone) ) {
        header("Location: signup.html?error=invalid_phone");
        exit();
    }

    // check email format
    if ( !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.html?error=invalid_email");
        exit();
    }

    // make sure email isn't already signed up to a userID
    $stmt = $conn->prepare("SELECT UserID FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.html?error=email_exists");
        exit();

    }

    $stmt->close();

    // signing up a new user --> update tables
    $result=$conn->query("SELECT MAX(UserID) as maxID FROM User");
    $row = $result->fetch_assoc();
    $newUserID = ($row['maxID'] ?? 0) + 1;       // increase unique UserId

    $hashedPassword = $password;    // store password
    
    // by default, users are adopters unless updated by admin
    $userRole = 'Adopter';
    // use placeholder/default values for other attributes
    //$phone = 'Not provided';
    //$address = 'Not provided';
    //$dob = '2000-01-01';        // placeholder default date

    $stmt =$conn->prepare("INSERT INTO User (UserID, password, userRole, firstName, lastName, email, phoneNum, address, dob) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $newUserID, $hashedPassword, $userRole, $firstName, $lastName, $email, $phone, $address, $dob);

    if ($stmt->execute()){      // put into adopter table
        $stmt2 =$conn->prepare("INSERT INTO Adopter (A_userID) VALUES (?)");
        $stmt2->bind_param("i", $newUserID);
        $stmt2->execute();
        $stmt2->close();

        header("Location: signup.html?success=1"); // redirect to login page
        exit();
    }
    else {
        header("Location: signup.html?error=insert_failed");
        exit();
    }

    $stmt->close();

}
$conn->close();
?>