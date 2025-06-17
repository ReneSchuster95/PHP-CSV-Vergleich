<?php
// Error message handler
$message = '';

switch (true) {
    case isset($_GET['no_file1']):
        $message = '❌ Bitte wähle die *alte* CSV-Datei aus.';
        break;
    case isset($_GET['no_file2']):
        $message = '❌ Bitte wähle die *neue* CSV-Datei aus.';
        break;
    case isset($_GET['no_post']):
        $message = '⚠️ Ungültiger Zugriff. Bitte benutze das Formular.';
        break;
    case isset($_GET['failed_to_read_old']):
        $message = '❌ Die *alte* CSV-Datei konnte nicht gelesen werden.';
        break;
    case isset($_GET['failed_to_read_new']):
        $message = '❌ Die *neue* CSV-Datei konnte nicht gelesen werden.';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>CSV Comparison</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>CSV Comparison</h1>

    <!-- Upload form for both CSV files -->
    <form action="vergleich.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <!-- File input for the existing database -->
        <label for="alte">Database:</label>
        <input type="file" name="old" id="old" accept=".csv" required><br><br>

        <!-- File input for the new catalog import -->
        <label for="neue">Catalog Import:</label>
        <input type="file" name="new" id="new" accept=".csv" required><br><br>

        <input type="submit" value="Compare" />
    </form>

    <!-- Display error message if present -->
    <?php if ($message): ?>
        <div class="error-box"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

</body>

</html>