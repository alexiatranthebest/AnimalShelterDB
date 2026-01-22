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
        header("Location: employee_signup.html?error=empty");
        exit();
    }

    // passwords match or password length is not < 6
    if ($password !== $confirmPassword) {
        header("Location: employee_signup.html?error=password_mismatch");
        exit();
    }
    if (strlen($password) < 6){
        header("Location: employee_signup.html?error=password_short");
        exit();
    }

    // check email format
    if ( !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: employee_signup.html?error=invalid_email");
        exit();
    }

    // check phone format
    if (!preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $phone) ) {
        header("Location:  employee_signup.html?error=invalid_phone");
        exit();
    }

    // make sure email isn't already signed up to a userID
    $stmt = $conn->prepare("SELECT UserID, userRole FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        // user exists --> update their info
        $user=$result->fetch_assoc();
        $existingUserID =$user['UserID'];
        $existingRole=$user['userRole'];

        $stmt->close();

        // user is already an employee --> error
        if ($existingRole=== 'Employee') {
            header("Location: employee_signup.html?error=already_employee");
            exit();
        }

        // if not already an employee --> update role to employee and update new info
        $hashedPassword = $password;
        $newRole = 'Employee';

        $stmt =$conn->prepare("UPDATE User SET password =?, userRole = ?, firstName =?, lastName = ?, phoneNum=?, address= ?, dob=? WHERE UserID =?");
        $stmt->bind_param("sssssssi", $hashedPassword, $newRole,$firstName, $lastName,$phone,$address, $dob, $existingUserID);
        
        if ($stmt->execute()) {
            
            $stmt->close();
            if ($existingRole ==='Adopter') {           // if role = adopter --> remove from adopter table
                $stmt2=$conn->prepare("DELETE FROM Adopter WHERE A_userID = ?");
                $stmt2->bind_param("i", $existingUserID);
                $stmt2->execute();
                $stmt2->close();

            }


            // insert into employee table instead
            $stmt3 =$conn->prepare("INSERT INTO Employee (E_userID) VALUES (?)");
            $stmt3->bind_param("i", $existingUserID);

            if ($stmt3->execute()) {
                $stmt3->close();
                header("Location: employee_signup.html?success=1&updated=true");
                exit();
            } 
            
            else {
                $stmt3->close();
                header("Location: employee_signup.html?error=employee_insert_failed");
                exit();
            }

        } 
        
        else {
            $stmt->close();
            header("Location: employee_signup.html?error=update_failed");
            exit();
        }

    }

    // user doesn't exist --> create a new user
    else {
        $stmt->close();

        // signing up a new user --> update tables
        $result=$conn->query("SELECT MAX(UserID) as maxID FROM User");
        $row = $result->fetch_assoc();
        $newUserID = ($row['maxID'] ?? 0) + 1;       // increase unique UserId

        $hashedPassword = $password;    // store password
        $userRole = 'Employee';


        $stmt= $conn->prepare("INSERT INTO User (UserID, password, userRole, firstName, lastName, email, phoneNum, address, dob) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $newUserID, $hashedPassword, $userRole, $firstName, $lastName, $email, $phone, $address, $dob );

        if ($stmt->execute()) {     // push into employee db table 
            $stmt2=$conn->prepare("INSERT INTO Employee (E_userID) VALUES (?)");
            $stmt2->bind_param("i", $newUserID);
            
            if ($stmt2->execute()) { // successfull
                $stmt2->close();
                header("Location: employee_signup.html?success=1");
                exit();
            } 
            else {                //unsuccessful
                $stmt2->close();
                header("Location: employee_signup.html?error=insert_failed");
                exit();
            }
        } 

        else {
            header("Location: employee_signup.html?error=insert_failed");
            exit();
        }
        $stmt->close();
    }

}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Animal Shelter - Employee Application</title>
    <link rel="stylesheet" href="employee_signup_styling.css">
</head>
<body>
    <h1><b>Local Animal Shelter</b></h1>
    <form action="" method="POST">
        <div class="container">
            <h2>Employee Application Form</h2>
            <h3>Personal Information</h3>
            <label for="fname"><b>First Name</b></label>
            <input type="text" id="fname" name="fname" placeholder="Enter first name" required><br>
            <label for="lname"><b>Last Name</b></label>
            <input type="text" id="lname" name="lname" placeholder ="Enter last name" required><br>

            <label for="date"><b>Date of Birth</b></label>
            <input type="date" id="date" name="date" max="2025-11-15" required><br>

            <h3>Current Address</h3>
            <label for="streetaddress"><b>Street Address</b></label>
            <input type="text" id="streetaddress" name="streetaddress" autocomplete="street-address"
                placeholder="Street Address" required><br>
           
        
            <h3>Contact Information</h3>
            <label for="email"><b>Email Address</b></label>
            <input type="email" id="email" name="email" placeholder="e.g. user@email.com" required><br>

            <label for="phonenum"><b>Phone Number</b></label>
            <input type="tel" id="phonenum" name="phonenum" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                placeholder="e.g. 123-456-7890" required><br>

            <h3>Login Information</h3>
            <label for="pass"><b>Password</b></label>
            <input type="password" id="pass" name="pass" placeholder ="Enter password" required><br>
            
            <label for="confirmpass"><b>Confirm Password</b></label>
            <input type="password" id="confirmpass" name="confirmpass" placeholder ="Enter password again" required><br>

            <button type="submit">Submit</button>
        </div>
    </form>

    <script>
        window.addEventListener('DOMContentLoaded', function(){
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const success = urlParams.get('success');
            const updated = urlParams.get('updated');
            if (error=== 'empty')
                alert('Fill in all fields.');
            else if (error ==='password_mismatch')
                alert('Passwords do not match.');
            else if (error === 'password_short')
                alert('Password needs to be at least 6 characters long.');
            else if (error === 'invalid_email')
                alert('Enter a valid email address');
            else if (error==='invalid_phone')
                alert('Phone number needs to be in the format: XXX-XXX-XXXX');
            else if (error ==='already_employee')
                alert('This email is already registered as an employee. Please login instead.')
            else if (error=== 'update_failed')
                alert('Unable to update account information. Please try again.')
            else if (error=== 'employee_insert_failed')
                alert('Failed to create employee record. Please try again.')
            else if (error === 'insert_failed')
                alert('Registration unsucessful. Please try again.');
            else if (success==='1' && updated === 'true') {
                alert('Account successfully updated to Employee. Redirecting to login...');
                setTimeout(function() {
                    window.location.href='login.html';
                }, 1500);
            }
            else if (success === '1') {
                alert('Employee account successfully created. Redirecting to login...');
                setTimeout(function() {
                    window.location.href='login.html';
                }, 1500);
            }
        });

        // make sure passwords match up
        document.querySelector('form').addEventListener('submit', function(e) {
            const password= document.getElementById('pass').value;
            const confirmPasswordMatch = document.getElementById('confirmpass').value;
            const phone = document.getElementById('phonenum').value;
            if (password !== confirmPasswordMatch) {
                e.preventDefault();
                alert('Passwords do not match up.');
                return false;
            }

            // make sure password length is > 6
            if (password.length < 6) {
                e.preventDefault();
                alert('Password needs to be at least 6 characters long.');
                return false;
            }

            if (!/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/.test(phone)) {
                e.preventDefault();
                alert('Phone number must be in format: XXX-XXX-XXXX');
                return false;
            } 
        });

    </script>

</body>
</html>