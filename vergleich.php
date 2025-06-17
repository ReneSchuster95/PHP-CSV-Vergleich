<?php
session_start();

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Check if both files are uploaded without errors
if (!isset($_FILES["old"]) || $_FILES["old"]['error'] !== UPLOAD_ERR_OK) {
    header("Location: index.php?no_file1");
    exit;
}
if (!isset($_FILES["new"]) || $_FILES["new"]['error'] !== UPLOAD_ERR_OK) {
    header("Location: index.php?no_file2");
    exit;
}

// Arrays to store CSV data
$oldData = [];
$newData = [];

// Load old CSV
if (($handle = fopen($_FILES["old"]['tmp_name'], 'r')) !== false) {
    while (($row = fgetcsv($handle, 1000, ';')) !== false) {
        if (count($row) >= 2) {
            $id = trim($row[0]);
            $desc = trim($row[1]);
            $oldData[$id] = $desc;
        }
    }
    fclose($handle);
} else {
    header("Location: index.php?failed_to_read_old");
    exit;
}

// Arrays for differences
$added = [];     // New entries
$changed = [];   // Changed entries

// Load and compare new CSV
if (($handle = fopen($_FILES["new"]['tmp_name'], 'r')) !== false) {
    while (($row = fgetcsv($handle, 1000, ';')) !== false) {
        if (count($row) >= 2) {
            $id = trim($row[0]);
            $desc = trim($row[1]);
            $newData[$id] = $desc;

            if (isset($oldData[$id])) {
                if ($oldData[$id] !== $desc) {
                    $changed[$id] = $desc;
                }
                // ID matched, so remove from oldData to simplify deleted detection
                unset($oldData[$id]);
            } else {
                $added[$id] = $desc;
            }
        }
    }
    fclose($handle);
} else {
    header("Location: index.php?failed_to_read_new");
    exit;
}

// Remaining entries in oldData are deleted ones
$deleted = $oldData;

// Function to render table rows
function renderTableRows(array $arr): void
{
    foreach ($arr as $id => $desc) {
        echo "<tr><td>" . htmlspecialchars($id) . "</td><td>" . htmlspecialchars($desc) . "</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Ergebnisse</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>CSV Vergleich</h1>

    <!-- Button zum Zurückkehren zur Startseite -->
    <form action="index.php" method="get" style="margin-bottom: 20px;">
        <button type="submit">Zurück zur Startseite</button>
    </form>

    <div class="container">

        <!-- New Entries -->
        <div class="CSVTable">
            <h3>New IDs:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                </tr>
                <?php renderTableRows($added); ?>
            </table>
            <form method="post" action="download.php">
                <input type="hidden" name="filename" value="New_IDs.csv">
                <input type="hidden" name="data" value="<?= base64_encode(serialize($added)) ?>">
                <button type="submit">Download CSV</button>
            </form>
        </div>

        <!-- Changed Entries -->
        <div class="CSVTable">
            <h3>Changed Descriptions:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>New Description</th>
                </tr>
                <?php renderTableRows($changed); ?>
            </table>
            <form method="post" action="download.php">
                <input type="hidden" name="filename" value="Changed_Descriptions.csv">
                <input type="hidden" name="data" value="<?= base64_encode(serialize($changed)) ?>">
                <button type="submit">Download CSV</button>
            </form>
        </div>

    </div>
</body>

</html>