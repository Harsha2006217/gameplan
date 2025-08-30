<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM Events WHERE event_id = :id AND user_id = :user");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':user', $user_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$event) header("Location: index.php");

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $description = trim($_POST['description']);
    $reminder = $_POST['reminder'];
    if (editEvent($id, $title, $date, $time, $description, $reminder)) {
        header("Location: index.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Fout bij update.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenement bewerken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Evenement bewerken</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required maxlength="100"></div>
            <div class="mb-3"><input type="date" name="date" class="form-control" value="<?php echo $event['date']; ?>" required min="<?php echo date('Y-m-d'); ?>"></div>
            <div class="mb-3"><input type="time" name="time" class="form-control" value="<?php echo $event['time']; ?>" required></div>
            <div class="mb-3"><textarea name="description" class="form-control"><?php echo htmlspecialchars($event['description']); ?></textarea></div>
            <div class="mb-3">
                <select name="reminder" class="form-select">
                    <option value="" <?php if (!$event['reminder']) echo 'selected'; ?>>Geen herinnering</option>
                    <option value="1 uur ervoor" <?php if ($event['reminder'] == '1 uur ervoor') echo 'selected'; ?>>1 uur ervoor</option>
                    <option value="1 dag ervoor" <?php if ($event['reminder'] == '1 dag ervoor') echo 'selected'; ?>>1 dag ervoor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</body>
</html>