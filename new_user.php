<?php

session_start();
// get all 3 strings from the form (and scrub w/ validation function)
include_once 'validate.php';
$endUser = test_input($_POST['user']);
$endUserPassword = test_input($_POST['pwd']);
$repeatPassword = test_input($_POST['repeat']);
// make sure that the two password values match!
if ($endUserPassword != $repeatPassword) {
    header("location: register.php");
} else {
    $hashedPassword = password_hash($endUserPassword, PASSWORD_DEFAULT);
}

// create the password_hash using the PASSWORD_DEFAULT argument
// login to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softball";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = $conn->prepare("SELECT * FROM users WHERE username = '$endUser'");
$sql->execute();
$sql->store_result();

if ($sql->num_rows > 0) {
    // User already exists, redirect back to register.php
    header("location: register.php");
    exit();
} else {
    $sql = "INSERT INTO users (username, password) VALUES ('$endUser','$hashedPassword')";
    $result = $conn->query($sql);
}

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

// insert username and password hash into db (put the username in the session
// or make them login)

