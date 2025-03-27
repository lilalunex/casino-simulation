<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Simulation</title>
</head>
<body>
<h1>Casino Simulation</h1>

<?php
require_once 'casino.php';

$casino = new Casino();
ob_start();
$casino->showWelcomeScreen();
$output = ob_get_clean();
//echo nl2br($output);
?>

<form action="index.php" method="POST">
    <label for="action">Choose an option:
        <select name="action">
            <option value="1">Simulate a day</option>
            <option value="7">Simulate a week</option>
            <option value="31">Simulate a month</option>
            <option value="365">Simulate a year</option>
        </select>
    </label>
    <button type="submit">Run Simulation</button>
</form>

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
