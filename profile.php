<?php
require 'functions.php';
if (!isLoggedIn()) header("Location: login.php");
$user_id = $_SESSION['user_id'];
$profile = getProfile($user_id);
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $favorite_games = trim($_POST['favorite_games']);
    if (saveProfile($user_id, $favorite_games)) {
        $message = '<div class="alert alert-success">Profiel opgeslagen.</div>';
        $profile = getProfile($user_id); // Refresh
    } else {
        $message = '<div class="alert alert-danger">Fout bij opslaan.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Profiel bewerken</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3"><label>Favoriete games</label><textarea name="favorite_games" class="form-control"><?php echo htmlspecialchars($profile['favorite_games']); ?></textarea></div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</body>
</html>