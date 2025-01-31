<?php

require_once 'config.php';
require_once 'db.php';

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectToLogin() {
    header("Location: login.php");
    exit();
}

function getTasks($user_id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        SELECT * FROM tasks 
        WHERE user_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTask($task_id, $user_id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        SELECT * FROM tasks 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$task_id, $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createTask($user_id, $title, $description, $priority) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        INSERT INTO tasks (user_id, title, description, priority) 
        VALUES (?, ?, ?, ?)
    ");
    return $stmt->execute([$user_id, $title, $description, $priority]);
}

function updateTask($task_id, $user_id, $title, $description, $priority, $status) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        UPDATE tasks 
        SET title = ?, description = ?, priority = ?, status = ? 
        WHERE id = ? AND user_id = ?
    ");
    return $stmt->execute([$title, $description, $priority, $status, $task_id, $user_id]);
}

function updateTaskStatus($task_id, $user_id, $status) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        UPDATE tasks 
        SET status = ? 
        WHERE id = ? AND user_id = ?
    ");
    return $stmt->execute([$status, $task_id, $user_id]);
}

function deleteTask($task_id, $user_id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        DELETE FROM tasks 
        WHERE id = ? AND user_id = ?
    ");
    return $stmt->execute([$task_id, $user_id]);
}

function getPriorityClass($priority) {
    return [
        'low' => 'text-success',
        'medium' => 'text-warning',
        'high' => 'text-danger'
    ][$priority] ?? 'text-secondary';
}

function getStatusClass($status) {
    return [
        'todo' => 'bg-light',
        'in_progress' => 'bg-info text-white',
        'done' => 'bg-success text-white'
    ][$status] ?? 'bg-light';
}

function getStatusText($status) {
    return [
        'todo' => 'To Do',
        'in_progress' => 'In Progress',
        'done' => 'Done'
    ][$status] ?? 'Unknown';
}