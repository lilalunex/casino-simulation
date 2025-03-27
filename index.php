<?php

ini_set('display_errors', 1);  // Enable displaying errors
error_reporting(E_ALL);        // Report all types of errors


$_GET['arg'] = 'web';

require_once 'src/casino.php';

$casino = new Casino();

$output = '';
$lost = false;

if (isset($_POST['simulate1'])) {
    ob_start();
    $casino->runSimulation(1);
    $output = ob_get_clean();
} else if (isset($_POST['simulate7'])) {
    ob_start();
    $casino->runSimulation(7);
    $output = ob_get_clean();
} else if (isset($_POST['simulate30'])) {
    ob_start();
    $casino->runSimulation(31);
    $output = ob_get_clean();
} else if (isset($_POST['simulate365'])) {
    ob_start();
    $casino->runSimulation(365);
    $output = ob_get_clean();
} else if (isset($_POST['giveup'])) {
    ob_start();
    $casino->giveUp();
    $lost = true;
    $output = ob_get_clean();
} else if (isset($_POST['restart'])) {
    $casino->restart();
    $lost = false;
    $output = 'Casino restarted.';
} else if (isset($_POST['customDays'])) {
    $customDays = intval($_POST['customDays']);
    if ($customDays > 0) {
        ob_start();
        $casino->runSimulation($customDays);
        $output = ob_get_clean();
    }
}

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        h1 {
            margin: 0 !important;
        }

        button {
            padding: .5rem 1.5rem;
        }
    </style>
</head>
<body style="max-width: 1440px; margin-left: auto; margin-right: auto; padding: 1.5rem; font-size: 20px; position:relative;">
<p>
    <a href='https://github.com/lilalunex/casino-simulation/' target='_blank'
       style="text-decoration: none">GitHub Link</a>, there you will also find all the other ideas I
    have to build uopen this.<br><br>
    <a href="https://lilalunex.dev/" style="text-decoration: none">Back to lilalunex.dev</a>
</p>
<?php
$casino->showWelcomeScreen();
?>
<?php
$casino->gameLoop();
?>

<form method="POST">
    <?php if (!$lost): ?>
        <button type="submit" name="simulate1">Simulate 1 day</button>
        <button type="submit" name="simulate7">Simulate 1 week</button>
        <button type="submit" name="simulate30">Simulate 30 days</button>
        <button type="submit" name="simulate365">Simulate 365 days</button>
        <br><br>
        <label for="customDays">Enter number of days to simulate:
            <input type="number" name="customDays" min="1" placeholder="e.g., 10">
        </label>
        <button type="submit">Simulate Custom Days</button>
        <br><br>
        <button type="submit" name="giveup">Give up</button>
    <?php else: ?>
        <button type="submit" name="restart">Restart</button>
    <?php endif ?>
</form>

<?php if ($output): ?>
    <div>
        <strong>Simulation Result:</strong><br>
        <?php echo $output; ?>
    </div>
<?php endif; ?>


<div style="padding-top: 3rem;">
    <button style="margin-left: auto; display: block" onclick="toggleHelp()">Information</button>
    <div id="help" style="display: none">
        <?php
        $casino->showHelpText();
        ?>
    </div>
</div>
<script>
    function toggleHelp() {
        var helpElement = document.getElementById("help");
        if (helpElement.style.display === "none") {
            helpElement.style.display = "block";
        } else {
            helpElement.style.display = "none";
        }
    }
</script>
</body>
</html>
