<?php
include "../db.php"; // Adjust path if necessary

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animalID     = rand(0, 99999);
    $name         = $_POST['name'];
    $ward         = $_POST['ward'];
    $breed        = $_POST['breed'] ?? null;
    $sex          = $_POST['sex'] ?? null;
    $yearOfBirth  = $_POST['yearOfBirth'] ?? null;
    $healthStatus = $_POST['healthStatus'] ?? null;
    $arrivalDate  = $_POST['arrivalDate'];

    // Prepare insert statement
    $sql = "INSERT INTO Animals
            (animalID, name, ward, breed, sex, healthStatus, yearOfBirth, arrivalDate, adoptionDate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0000-00-00)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $animalID, $name, $ward, $breed, $sex, $healthStatus, $yearOfBirth, $arrivalDate);

    if ($stmt->execute()) {
        $message = "Animal '$name' added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Animal</title>
    <!--<link rel="stylesheet" href="../css/greenstyle.css"> -->
</head>
<body>

<h2>Add Animal To Database</h2>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form method="POST" action="">

    <label>Animal Name:</label>
    <input type="text" name="name" required>
    <br><br>

    <label>Ward:</label>
    <select name="ward" required>
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
        <option value="Rabbit">Rabbit</option>
        <option value="Bird">Bird</option>
        <option value="Rodent">Rodent</option>
    </select>
    <br><br>

    <label>Breed:</label>
    <input type="text" name="breed">
    <br><br>

    <label>Sex:</label>
    <select name="sex">
        <option value="">--Select--</option>
        <option value="Female">Female</option>
        <option value="Male">Male</option>
        <option value="Intersex">Intersex</option>
    </select>
    <br><br>

    <label>Year of Birth:</label>
    <input type="text" name="yearOfBirth">
    <br><br>

    <label>Health Status:</label>
    <input type="text" name="healthStatus">
    <br><br>

    <label>Arrival Date:</label>
    <input type="date" name="arrivalDate" required>
    <br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>

<?php
$conn->close();
?>
