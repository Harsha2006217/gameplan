<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$user_id = $_SESSION['user_id'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $description = trim($_POST['description']);
    $reminder = $_POST['reminder'];
    if (addEvent($user_id, $title, $date, $time, $description, $reminder)) {
        $message = '<div class="alert alert-success">Evenement toegevoegd.</div>';
    } else {
        $message = '<div class="alert alert-danger">Fout: controleer inputs.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenement toevoegen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Evenement toevoegen</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="title" class="form-control" placeholder="Titel" required maxlength="100"></div>
            <div class="mb-3"><input type="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>"></div>
            <div class="mb-3"><input type="time" name="time" class="form-control" required></div>
            <div class="mb-3"><textarea name="description" class="form-control" placeholder="Beschrijving"></textarea></div>
            <div class="mb-3">
                <select name="reminder" class="form-select">
                    <option value="">Geen herinnering</option>
                    <option value="1 uur ervoor">1 uur ervoor</option>
                    <option value="1 dag ervoor">1 dag ervoor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Toevoegen</button>
        </form>
    </div>
</body>
</html>