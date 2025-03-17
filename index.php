<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Simulation</title>
</head>
<body>
<h1>Casino Simulation 2</h1>

<?php
require_once 'casino.php';
var_dump(class_exists('Casino'));


$casino = new Casino();
ob_start(); // Capture output
$casino->showWelcomeScreen();
$output = ob_get_clean(); // Store output in variable
echo nl2br($output); // Convert newlines to HTML <br>
?>

<form action="index.php" method="POST">
    <label for="action">Choose an option:</label>
    <select name="action">
        <option value="1">Simulate a day</option>
        <option value="7">Simulate a week</option>
        <option value="31">Simulate a month</option>
        <option value="365">Simulate a year</option>
    </select>
    <button type="submit">Run Simulation</button>
</form>

<div>hi</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $days = intval($_POST["action"] ?? 1);
    ob_start();
    $casino->runSimulation($days);
    $simulationResult = ob_get_clean();
    echo nl2br($simulationResult);
}
?>
</body>
</html>
