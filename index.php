<?php
//Error Nachrichten
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
<html>

<head>
    <meta charset="utf-8" />
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>CSV Vergleich</h1>
    <form action="vergleich.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <label for="alte">CSV-Datei Alte:</label>
        <input type="file" name="alte" id="alte" accept=".csv" required><br><br>

        <label for="neue">CSV-Datei neue:</label>
        <input type="file" name="neue" id="neue" accept=".csv" required><br><br>

        <input type="submit" value="Vergleichen" />
    </form>

    <?php if ($message): ?>
        <div class="error-box"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</body>

</html>