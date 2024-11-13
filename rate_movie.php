<?php
// Assuming you have a database connection established
global $databaseHost;
global $databasePassword;
global $databaseUsername;
global $databaseName;
include ("connection.php");

$movieId = $_POST['movieId'];
$rating = $_POST['rating'];
$userId = $_SESSION['user_id']; // Replace with your user authentication method

// Insert or update the rating in the database
$sql = "INSERT INTO movie_ratings (user_id, movie_id, rating) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE rating = ?";

$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ionic', $userId,$movieId, $rating, $rating);
$stmt->execute();

// Return a response to the client
echo json_encode(['success' => true]);