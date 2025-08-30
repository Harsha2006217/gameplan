CREATE DATABASE gameplan_db;
USE gameplan_db;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    favorite_games TEXT
);

CREATE TABLE Friends (
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    friend_user_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (friend_user_id) REFERENCES Users(user_id)
);

CREATE TABLE Schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    game VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    friends TEXT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    description TEXT,
    reminder VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Voorbeeld data
INSERT INTO Users (username, email, password_hash, favorite_games) VALUES 
('testuser', 'test@example.com', '$2y$10$K.IwX0z0/0q0K.IwX0z0/0q0K.IwX0z0/0q0K.IwX0z0/0q0K.IwX0z0/', 'Fortnite, Minecraft');