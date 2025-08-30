<?php
session_start();
require 'db.php';

// Functie om wachtwoord te hashen
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Functie om gebruiker in te loggen
function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        session_regenerate_id(); // Security: nieuwe sessie ID
        return true;
    }
    return false;
}

// Functie om ingelogde gebruiker te checken
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Functie om profiel op te slaan
function saveProfile($user_id, $favorite_games) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE Users SET favorite_games = :games WHERE user_id = :id");
    $stmt->bindParam(':games', $favorite_games);
    $stmt->bindParam(':id', $user_id);
    return $stmt->execute();
}

// Functie om vriend toe te voegen
function addFriend($user_id, $friend_user_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_user_id) VALUES (:user, :friend)");
    $stmt->bindParam(':user', $user_id);
    $stmt->bindParam(':friend', $friend_user_id);
    return $stmt->execute();
}

// Functie om schema toe te voegen
function addSchedule($user_id, $game, $date, $time, $friends) {
    global $pdo;
    if (empty($game) || strtotime($date) < time()) { // Validatie: geen lege game, toekomstige datum
        return false;
    }
    $friends_str = implode(',', $friends); // Array naar string
    $stmt = $pdo->prepare("INSERT INTO Schedules (user_id, game, date, time, friends) VALUES (:user, :game, :date, :time, :friends)");
    $stmt->bindParam(':user', $user_id);
    $stmt->bindParam(':game', $game);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':friends', $friends_str);
    return $stmt->execute();
}

// Soortgelijke functies voor events, edit, delete...
function addEvent($user_id, $title, $date, $time, $description, $reminder) {
    global $pdo;
    if (empty($title) || strlen($title) > 100 || strtotime($date) < time()) { // Validatie: titel max 100, toekomst
        return false;
    }
    $stmt = $pdo->prepare("INSERT INTO Events (user_id, title, date, time, description, reminder) VALUES (:user, :title, :date, :time, :desc, :rem)");
    $stmt->bindParam(':user', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':desc', $description);
    $stmt->bindParam(':rem', $reminder);
    return $stmt->execute();
}

function editEvent($event_id, $title, $date, $time, $description, $reminder) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE Events SET title = :title, date = :date, time = :time, description = :desc, reminder = :rem WHERE event_id = :id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':desc', $description);
    $stmt->bindParam(':rem', $reminder);
    $stmt->bindParam(':id', $event_id);
    return $stmt->execute();
}

function deleteEvent($event_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Events WHERE event_id = :id");
    $stmt->bindParam(':id', $event_id);
    return $stmt->execute();
}

// Functie voor vriendenlijst ophalen
function getFriends($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT u.username FROM Friends f JOIN Users u ON f.friend_user_id = u.user_id WHERE f.user_id = :user");
    $stmt->bindParam(':user', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie voor schema's ophalen
function getSchedules($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Schedules WHERE user_id = :user");
    $stmt->bindParam(':user', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie voor events ophalen
function getEvents($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Events WHERE user_id = :user");
    $stmt->bindParam(':user', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie voor profiel ophalen
function getProfile($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Functie voor logout
function logout() {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>