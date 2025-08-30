<?php
require 'functions.php';
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($username) || strlen($username) > 50) {
        $error = 'Username verplicht, max 50 tekens.';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = 'Ongeldige email.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord minstens 6 tekens.';
    } else {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:user, :email, :pass)");
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':email', $email);
        $hash = hashPassword($password);
        $stmt->bindParam(':pass', $hash);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = 'Email bestaat al.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registreren</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required maxlength="50"></div>
            <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Wachtwoord" required minlength="6"></div>
            <button type="submit" class="btn btn-primary">Registreren</button>
        </form>
    </div>
</body>
</html>