<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

function createTable($conn, $table) {
    $stmt = NULL;

    if ($table === "users") {
        $stmt = "CREATE TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
            )";
    }
    elseif ($table === "profiles") {
        $stmt = "CREATE TABLE IF NOT EXISTS profiles(
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            profile VARCHAR(255)
            )";
    }
    elseif ($table[0] === "notes") {
        $stmt = "CREATE TABLE IF NOT EXISTS `$table[1]`(
            id INT AUTO_INCREMENT PRIMARY KEY,
            note VARCHAR(255)
            )";
    }

    if ($stmt !== NULL) {
        $conn->query($stmt);
        return TRUE;
    } 
    else {
        return FALSE;
    }
    
}

function connect() {
    $servername = "localhost";
    $DbUser     = "ctf";
    $password   = "password";
    $database   = "bi0s";

    $conn = new mysqli($servername, $DbUser, $password, $database);
    createTable($conn, "users");
    createTable($conn, "profiles");

    return $conn->connect_error ? NULL : $conn;
}

function insert($conn, $table, $value) {
    if ($table === "users") {
        $stmt = $table && $value ? $conn->prepare("INSERT INTO `$table`(username, password) VALUES(?, ?)") : NULL;
    } 
    elseif ($table === "profiles") {
        $stmt = $table && $value ? $conn->prepare("INSERT INTO `$table`(username, profile) VALUES(?, ?)") : NULL;
    }
    else {
        $stmt = $table && $value ? $conn->prepare("INSERT INTO `$table`(note) VALUES(?)") : NULL;
    }
    if ($stmt !== NULL) {
        if ($table === "users" || $table === "profiles") {
            $stmt->bind_param("ss", $value[0], $value[1]);
        } else {
            $stmt->bind_param("s", $value);
        }
        $executed = $stmt->execute();
        $stmt->close();
        return TRUE;
    } 
    else {
        return FALSE;
    }
}

function query($conn, $query, $user) {
    if ($conn && $query) {
        $stmt = $conn->prepare($query);
        if ($user) {
            $stmt->bind_param("s", $user);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row;
    }
    else {
        return FALSE;
    }
}

function find($conn, $user, $option) {
    if ($option === "table") {
        $result = $conn->query("SHOW TABLES LIKE '$user'");
        if ($result->num_rows > 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    } elseif ($option === "user") {
        
    }
}

function delete_or_update($conn, $query, $user) {
    if ($conn && $query && !is_array($user)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->close();

        return $result;
    }
    elseif ($conn && $query && is_array($user)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $user[0], $user[1]);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
    else {
        return FALSE;
    }
}

$conn = connect();

if (!$conn) {
    die('<p class="error" style="color: orange;">Error: Couldnt Connect </p>');
}
?>