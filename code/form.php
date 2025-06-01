<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles.css">
  <title>Přidat titul</title>
</head>
<body>
<?php
require_once 'db.php';

$types = $mysqli->query("SELECT id_type, type_name FROM types");
$statuses = $mysqli->query("SELECT id_status, status_name FROM statuses");
$genres = $mysqli->query("SELECT id_genre, genre_name FROM genres");
$titles = $mysqli->query("SELECT id_title, en_name FROM titles");
$relation_types = $mysqli->query("SELECT id_relation_type, type_key FROM relation_types");
?>

<form method="POST" action="insert.php" enctype="multipart/form-data">
  <label>EN Name:</label><br>
  <input type="text" name="en_name" required><br><br>

  <label>JP Name:</label><br>
  <input type="text" name="jp_name" required><br><br>

  <label>Synopsis:</label><br>
  <textarea name="synopsis" required></textarea><br><br>

  <label>Obrázek:</label><br>
  <input type="file" name="image" accept="image/*"><br><br>

  <label>Type:</label><br>
  <select name="id_type" required>
    <?php while ($row = $types->fetch_assoc()): ?>
      <option value="<?= $row['id_type'] ?>"><?= htmlspecialchars($row['type_name']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Status:</label><br>
  <select name="id_status" required>
    <?php while ($row = $statuses->fetch_assoc()): ?>
      <option value="<?= $row['id_status'] ?>"><?= htmlspecialchars($row['status_name']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Genres (drž Ctrl pro víc výběrů):</label><br>
  <select name="genres[]" multiple required>
    <?php while ($row = $genres->fetch_assoc()): ?>
      <option value="<?= $row['id_genre'] ?>"><?= htmlspecialchars($row['genre_name']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Episodes:</label><br>
  <input type="number" name="episodes" min="1" required><br><br>

  <label>Series Name (volitelné):</label><br>
  <input type="text" name="series_name"><br><br>

  <label>Related Title (volitelné):</label><br>
  <select name="id_related_title">
    <option value="">-- žádný --</option>
    <?php while ($row = $titles->fetch_assoc()): ?>
      <option value="<?= $row['id_title'] ?>"><?= htmlspecialchars($row['en_name']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Relation Type (pokud vyplněn Related Title):</label><br>
  <select name="id_relation_type">
    <option value="">-- žádný --</option>
    <?php while ($row = $relation_types->fetch_assoc()): ?>
      <option value="<?= $row['id_relation_type'] ?>"><?= htmlspecialchars($row['type_key']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <button type="submit">Uložit</button>
</form>
</body>
</html>
