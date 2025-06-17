# PHP-CSV-Vergleich

Ein einfaches PHP-Tool zum Vergleichen von zwei CSV-Dateien im Format `ID;Bezeichnung`.

## 📄 Format

Beide CSV-Dateien müssen folgende Struktur haben:

ID;Bezeichnung
101;Artikel A
102;Artikel B

- **ID**: Eine eindeutige numerische Kennung.
- **Bezeichnung**: Eine Beschreibung oder der Name des Artikels.

## 🔍 Funktionsweise

1. Die alte und die neue CSV-Datei werden über ein Webformular hochgeladen.
2. Das Tool vergleicht beide Dateien und erkennt:
   - **Neue Einträge**: IDs, die nur in der neuen Datei vorkommen.
   - **Geänderte Einträge**: Gleiche ID, aber andere Bezeichnung.
3. Die Unterschiede werden tabellarisch dargestellt.
4. Für jede Liste (neu, geändert) kann eine separate CSV-Datei heruntergeladen werden.

## ✅ Verwendung

1. Projekt in einen Webserver-Ordner legen (z. B. `htdocs` bei XAMPP).
2. Auf `index.php` im Browser zugreifen.
3. Zwei CSV-Dateien auswählen:
   - **Datenbank** = alte Datei (Bestand)
   - **Katalog-Import** = neue Datei (aktuelle Daten)
4. Auf **„Vergleichen“** klicken.
5. Die Ergebnisse einsehen und optional als CSV-Datei herunterladen.

## 📁 Dateien

- `index.php` – Upload-Formular
- `vergleich.php` – Vergleichslogik
- `download.php` – Export der Änderungen als CSV
- `style.css` – Einfaches Styling
- `db.php`, `speichern.php` – optional: Datenbankintegration möglich

## ⚠️ Hinweise

- Die CSV-Dateien **müssen UTF-8-kodiert** sein.
- Spaltentrenner: **Semikolon** (`;`)
- Es werden keine doppelten IDs unterstützt.

## 🛠 Beispiel

**alte.csv**

101;Apfel
102;Banane
103;Birne

**neue.csv**

101;Apfel
102;Banane Premium
104;Orange


**Ergebnis:**
- Neue: `104`
- Geändert: `102`
- Gelöscht: `103`

