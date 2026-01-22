<?php include "../db.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Adoption Center</title>
    <link rel="stylesheet" href="employeeView.css">
</head>
<body>

<h1>Employee View</h1>

<div class="menu">
    
<!-- 
    <a href="../animals/addAnimal.php" class="btn">Add Animal</a>
    <a href="../animals/updateAnimal.php" class="btn">Update Animal</a>
    <a href="../adoption/adoptionForm.php" class="btn">Adoption Application</a>
    <a href="../adoption/processAdoption.php" class="btn">Adoption Processing</a> -->
    <button class = "standard" onclick = "window.location.href = '../animals/addAnimal.php'">Add Animals</button><br>
    <button class = "standard" onclick = "window.location.href = '../animals/updateAnimal.php'">Update Animals</button><br>
    <button class = "standard" onclick = "window.location.href = '../adoption/processAdoption.php'">Process Adoption Requests</button>
    <button class = "standard" onclick = "window.location.href = '../signIn/login.html'">Sign Out</button>

</div>

</body>
</html>
