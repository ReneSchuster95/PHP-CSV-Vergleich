<?php
session_start();

// Prüfen, ob das Formular per POST gesendet wurde
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Prüfen, ob die erste Datei hochgeladen wurde
if (!isset($_FILES["alte"]) || $_FILES["alte"]['error'] !== UPLOAD_ERR_OK) {
    header("Location: index.php?no_file1");
    exit;
}

// Prüfen, ob die zweite Datei hochgeladen wurde
if (!isset($_FILES["neue"]) || $_FILES["neue"]['error'] !== UPLOAD_ERR_OK) {
    header("Location: index.php?no_file2");
    exit;
}

// Arrays zur Speicherung der CSV-Daten
$csvAlt = [];
$csvNeu = [];

// Referenzen auf die hochgeladenen Dateien
$file1 = $_FILES["alte"];
$file2 = $_FILES["neue"];

// Einlesen der alten CSV-Datei
if (($handle = fopen($file1['tmp_name'], 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ';')) !== false) {
        // Es müssen genau zwei Spalten vorhanden sein
        if (count($data) == 2) {
            $csvAlt[$data[0]] = $data[1];
        }
    }
    fclose($handle);
} else {
    // Datei konnte nicht gelesen werden
    header("Location: index.php?failed_to_read_old");
    exit;
}

// Einlesen der neuen CSV-Datei
if (($handle = fopen($file2['tmp_name'], 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ';')) !== false) {
        // Es müssen mindestens zwei Spalten vorhanden sein
        if (count($data) >= 2) {
            $csvNeu[$data[0]] = $data[1];
        }
    }
    fclose($handle);
} else {
    // Datei konnte nicht gelesen werden
    header("Location: index.php?failed_to_read_new");
    exit;
}

// Arrays zur Speicherung der Änderungen
$newIDs = [];   // Neue Einträge
$delIDs = [];   // Gelöschte Einträge
$change = [];   // Geänderte Bezeichnungen

// Vergleich der alten CSV mit der neuen
foreach ($csvAlt as $id => $bezeichnung) {
    if (isset($csvNeu[$id])) {
        // Prüfen, ob sich die Bezeichnung geändert hat
        if ($csvNeu[$id] != $csvAlt[$id]) {
            $change[$id] = $csvNeu[$id];
        }
    } else {
        // ID existiert nicht mehr -> als gelöscht markieren
        $delIDs[$id] = $csvAlt[$id];
    }
}

// Ermittlung neuer Einträge (IDs, die vorher nicht existierten)
foreach ($csvNeu as $id => $bezeichnung) {
    if (!isset($csvAlt[$id])) {
        $newIDs[$id] = $csvNeu[$id];
    }
}

// Funktion zur Ausgabe einer ID-Liste als HTML-Tabelle
function outputArray($arr)
{
    foreach ($arr as $id => $bezeichnung) {
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$bezeichnung</td>";
        echo "</tr>";
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

        <!-- Neue IDs -->
        <div class="CSVTable">
            <h3>Neue IDs:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Bezeichnung</th>
                </tr>
                <?php
                // Gibt alle neuen IDs in einer Tabelle aus
                outputArray($newIDs);
                ?>
            </table>
            <form method="post" action="download.php">
                <!-- Übergabe des Dateinamens -->
                <input type="hidden" name="filename" value="Neue_IDs.csv">
                <!-- Übergabe der Daten als base64-kodiertes, serialisiertes Array -->
                <input type="hidden" name="data" value="<?php echo base64_encode(serialize($newIDs)); ?>">
                <button type="submit">Download CSV</button>
            </form>
        </div>

        <!-- Gelöschte IDs -->
        <div class="CSVTable">
            <h3>Gelöschte IDs:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Bezeichnung</th>
                </tr>
                <?php
                // Gibt alle gelöschten IDs in einer Tabelle aus
                outputArray($delIDs);
                ?>
            </table>
            <form method="post" action="download.php">
                <!-- Übergabe des Dateinamens -->
                <input type="hidden" name="filename" value="Geloeschte_IDs.csv">
                <!-- Übergabe der Daten als base64-kodiertes, serialisiertes Array -->
                <input type="hidden" name="data" value="<?php echo base64_encode(serialize($delIDs)); ?>">
                <button type="submit">Download CSV</button>
            </form>
        </div>

        <!-- Geänderte Bezeichnungen -->
        <div class="CSVTable">
            <h3>Geänderte Bezeichnungen:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Bezeichnung</th>
                </tr>
                <?php
                // Gibt alle geänderten Bezeichnungen in einer Tabelle aus
                outputArray($change);
                ?>
            </table>
            <form method="post" action="download.php">
                <!-- Übergabe des Dateinamens -->
                <input type="hidden" name="filename" value="Geaenderte_Bezeichnungen.csv">
                <!-- Übergabe der Daten als base64-kodiertes, serialisiertes Array -->
                <input type="hidden" name="data" value="<?php echo base64_encode(serialize($change)); ?>">
                <button type="submit">Download CSV</button>
            </form>
        </div>

    </div>
</body>

</html>