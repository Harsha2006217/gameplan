<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
// Haal schedule op
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM Schedules WHERE schedule_id = :id AND user_id = :user");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':user', $user_id);
$stmt->execute();
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$schedule) header("Location: index.php");

$friends_list = getFriends($user_id);
$selected_friends = explode(',', $schedule['friends']);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game = trim($_POST['game']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $friends = $_POST['friends'] ?? [];
    // Update logica (gelijkaardig aan add, maar UPDATE query)
    $friends_str = implode(',', $friends);
    $stmt = $pdo->prepare("UPDATE Schedules SET game = :game, date = :date, time = :time, friends = :friends WHERE schedule_id = :id");
    $stmt->bindParam(':game', $game);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':friends', $friends_str);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
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
    <title>Schema bewerken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Schema bewerken</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="game" class="form-control" value="<?php echo htmlspecialchars($schedule['game']); ?>" required></div>
            <div class="mb-3"><input type="date" name="date" class="form-control" value="<?php echo $schedule['date']; ?>" required min="<?php echo date('Y-m-d'); ?>"></div>
            <div class="mb-3"><input type="time" name="time" class="form-control" value="<?php echo $schedule['time']; ?>" required></div>
            <div class="mb-3">
                <label>Vrienden selecteren:</label>
                <?php foreach ($friends_list as $friend): ?>
                    <div class="form-check"><input type="checkbox" name="friends[]" value="<?php echo $friend['username']; ?>" class="form-check-input" <?php if (in_array($friend['username'], $selected_friends)) echo 'checked'; ?>> <?php echo htmlspecialchars($friend['username']); ?></div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</body>
</html>