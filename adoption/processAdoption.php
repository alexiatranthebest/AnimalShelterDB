<?php
include "../db.php"; // Adjust the path if needed

// Initialize message
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adoptionID   = $_POST['adoptionID'];
    $employeeID   = $_POST['employeeID'];
    $animalID     = $_POST['animalID']; 
    $requestStatus = $_POST['requestStatus']; // Get status from dropdown


    $fetchSql = "SELECT requestDate FROM AdoptionRequest WHERE adoptionID = ?";
    $fetchStmt = $conn->prepare($fetchSql);
    $fetchStmt->bind_param("i", $adoptionID);
    $fetchStmt->execute();
    $fetchStmt->bind_result($requestDate);
    $fetchStmt->fetch();
    $fetchStmt->close();

    // Insert into AdoptionRequest table
    $sql = "UPDATE AdoptionRequest 
            SET E_userID = ?, requestStatus = ?, requestDate = ?
            WHERE adoptionID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi",  $employeeID, $requestStatus, $requestDate, $adoptionID);

    if ($stmt->execute()) {
        // Update animal status if request is approved or pending
        if ($requestStatus === 'approved') {
            $updateSql = "UPDATE Animals SET adoptionDate = ? WHERE animalID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $requestDate, $animalID);
            $updateStmt->execute();
            $updateStmt->close();
        }
        $message = "Adoption Request Submitted Successfully! Request ID: $adoptionID";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Query available animals (status not 'adopted')
$sql_animals = "SELECT animalID, name, ward FROM Animals WHERE adoptionDate = 0000-00-00";
$result_animals = $conn->query($sql_animals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Adoption Processing</title>
</head>
<body>

<h2>Animal Adoption Processing</h2>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form action="" method="POST">
    <label for="adoptionID">AdoptionID:</label>
    <input type="number" name="adoptionID" id="adoptionID" required>
    <br><br>

    <label for="employeeID">EmployeeID:</label>
    <input type="number" name="employeeID" id="employeeID" required>
    <br><br>

    <label for="animalID">Animal:</label>
    <select name="animalID" id="animalID" required>
        <option value="">--Select an animal--</option>
        <?php
        if ($result_animals->num_rows > 0) {
            while ($row = $result_animals->fetch_assoc()) {
                echo "<option value='{$row['animalID']}'>"
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

    <label for="requestStatus">Request Status:</label>
    <select name="requestStatus" id="requestStatus" required>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>
    <br><br>
<!--
    <label for="requestDate">Adoption Processing Date:</label>
    <input type="date" name="requestDate" id="requestDate" required>
    <br><br>
-->

    <button type="submit">Submit</button>


</form>
</body>
</html>

<?php
$conn->close();
?>
