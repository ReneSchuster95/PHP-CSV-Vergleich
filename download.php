<?php
// Überprüfen, ob die Anfrage per POST erfolgt ist und die erwarteten Felder vorhanden sind
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['data'], $_POST['filename'])) {
    echo "Ungültiger Zugriff.";
    exit;
}

// Daten aus dem Formular entschlüsseln und deserialisieren
$data = unserialize(base64_decode($_POST['data']));

// Sicherheitsprüfung
$filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $_POST['filename']);

// Setzt Header, damit der Browser den Inhalt als CSV-Datei zum Download behandelt
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Pragma: no-cache');
header('Expires: 0');

// Öffnet eine Ausgabeverbindung zum Browser
$fp = fopen('php://output', 'w');

// Schreibt jede Zeile des Arrays als CSV-Zeile in die Ausgabeverbindung
foreach ($data as $id => $bezeichnung) {
    fputcsv($fp, [$id, $bezeichnung], ';');
}

// Schließt den Stream
fclose($fp);

// Beendet das Skript explizit
exit;
?>