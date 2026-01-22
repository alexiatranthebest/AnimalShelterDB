<?php
include "../db.php"; // Adjust path if needed

// Initialize a message variable
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adopterID   = filter_input(INPUT_POST, 'userID', FILTER_VALIDATE_INT);
    $animalID    =filter_input(INPUT_POST, 'animalID', FILTER_VALIDATE_INT);
    $requestDate = filter_input(INPUT_POST, 'requestDate', FILTER_SANITIZE_STRING);
    if ($adopterID === false || $animalID === false || empty($requestDate)) {
        die("Invalid input. Please check your entries.");
    } 

    // Generate adoptionID (or use AUTO_INCREMENT in your table)
    $adoptionID = rand(10000, 99999);

    $sql = "INSERT INTO AdoptionRequest 
            (adoptionID, A_userID, animalID, requestDate ,requestStatus, E_userID, adoptionFee)
            VALUES (?, ?, ?, ?, 'pending' ,NULL, NULL)"; // Employee not assigned yet

    $requestDate = $_POST['requestDate'];
    echo $requestDate;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iiis", $adoptionID, $adopterID, $animalID, $requestDate);

    if ($stmt->execute()) {
        $message = "Adoption Request Submitted Successfully! Request ID: $adoptionID";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch available animals dynamically
$sql_animals = "SELECT animalID, name, ward FROM Animals WHERE adoptionDate=0000-00-00";
$result_animals = $conn->query($sql_animals);
if (!$result_animals) {
    die("Error fetching animals: " . $conn->error);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Adoption Application</title>
</head>
<body>

<h2>Animal Adoption Application</h2>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form action="" method="POST">

    <!-- Adopter selection (dropdown recommended to prevent FK errors) -->
    <label for="userID">AdopterID:</label>
    <input type="number" name="userID" id="userID" required>
    <br><br>

    <!-- Animals dropdown -->
    <label for="animalID">Select Animal:</label>
    <select name="animalID" id="animalID" required>
        <option value="">-- Select an animal --</option>
        <?php
        if ($result_animals->num_rows > 0) {
            while ($row = $result_animals->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['animalID'])."'>"
                     . htmlspecialchars($row['name']) . " - "
                     . htmlspecialchars($row['ward']) 
                     . "</option>";
            }
        } else {
            echo "<option value=''>No animals available</option>";
        }
        ?>
    </select>
    <br><br>

    <!-- Request date -->
    <label for="requestDate">Adoption Request Date:</label>
    <input type="date" name="requestDate" id="requestDate" required>
    <br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>

<?php
$conn->close();
?>