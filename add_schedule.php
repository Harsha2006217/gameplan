<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$user_id = $_SESSION['user_id'];
$friends_list = getFriends($user_id); // Voor checkboxes
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game = trim($_POST['game']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $friends = $_POST['friends'] ?? [];
    if (addSchedule($user_id, $game, $date, $time, $friends)) {
        $message = '<div class="alert alert-success">Schema toegevoegd.</div>';
    } else {
        $message = '<div class="alert alert-danger">Fout: controleer inputs (bijv. toekomstige datum).</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schema toevoegen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Schema toevoegen</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="game" class="form-control" placeholder="Game" required></div>
            <div class="mb-3"><input type="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>"></div>
            <div class="mb-3"><input type="time" name="time" class="form-control" required></div>
            <div class="mb-3">
                <label>Vrienden selecteren:</label>
                <?php foreach ($friends_list as $friend): ?>
                    <div class="form-check"><input type="checkbox" name="friends[]" value="<?php echo $friend['username']; ?>" class="form-check-input"> <?php echo htmlspecialchars($friend['username']); ?></div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary">Toevoegen</button>
        </form>
    </div>
</body>
</html>