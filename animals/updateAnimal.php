<?php
include "../db.php"; // adjust path if necessary

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animalID     = $_POST['animalID'];
    $ward         = $_POST['ward'] ?? null;
    $sex          = $_POST['sex'] ?? null;
    $yearOfBirth  = $_POST['birth'] ?? null;
    $healthStatus = $_POST['healthStatus'] ?? null;
    $arrivalDate  = $_POST['arrivalDate'] ?? null;

    // Build dynamic SQL based on which fields are provided
    $fields = [];
    $params = [];
    $types  = "";

    if ($ward) { $fields[] = "ward=?"; $params[] = $ward; $types .= "s"; }
    if ($sex) { $fields[] = "sex=?"; $params[] = $sex; $types .= "s"; }
    if ($yearOfBirth) { $fields[] = "yearOfBirth=?"; $params[] = $yearOfBirth; $types .= "s"; }
    if ($healthStatus) { $fields[] = "healthStatus=?"; $params[] = $healthStatus; $types .= "s"; }
    if ($arrivalDate) { $fields[] = "arrivalDate=?"; $params[] = $arrivalDate; $types .= "s"; }

    if (count($fields) > 0) {
        $sql = "UPDATE Animals SET " . implode(", ", $fields) . " WHERE animalID=?";
        $params[] = $animalID;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $message = "Animal with ID $animalID updated successfully!";
        } else {
            $message = "Error updating animal: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "No fields to update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Animal</title>
</head>
<body>

<h2>Update Animal In Database</h2>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form method="POST" action="">
    <!-- Animal ID -->
    <label for="animalID">Animal ID:</label>
    <input type="number" id="animalID" name="animalID" placeholder="Enter the animal ID" required>
    <br><br>

    <!-- Ward -->
    <label for="ward">Ward:</label>
    <select id="ward" name="ward">
        <option value="">-- select --</option>
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
        <option value="Bird">Bird</option>
        <option value="Rabbit">Rabbit</option>
        <option value="Rodent">Rodent</option>
    </select>
    <br><br>

    <!-- Sex -->
    <label for="sex">Sex:</label>
    <select id="sex" name="sex">
        <option value="">-- select --</option>
        <option value="Female">Female</option>
        <option value="Male">Male</option>
        <option value="Intersex">Intersex</option>
    </select>
    <br><br>

    <!-- Year of Birth -->
    <label for="birth">Year of Birth:</label>
    <input type="text" id="birth" name="birth" placeholder="Enter the year of birth">
    <br><br>

    <!-- Health Status -->
    <label for="healthStatus">Health Status Notes:</label>
    <input type="text" id="healthStatus" name="healthStatus" placeholder="Enter health status">
    <br><br>

    <!-- Arrival Date -->
    <label for="arrivalDate">Arrival Date:</label>
    <input type="date" id="arrivalDate" name="arrivalDate">
    <br><br>

    <button type="submit">Update Animal</button>
</form>

</body>
</html>

<?php
$conn->close();
?>
