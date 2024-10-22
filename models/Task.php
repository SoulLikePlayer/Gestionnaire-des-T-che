<?php
require_once 'config.php';

class Task {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getAllTasks() {
        $stmt = $this->pdo->query('SELECT * FROM tasks');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTask($title, $description) {
        $stmt = $this->pdo->prepare('INSERT INTO tasks (title, description) VALUES (:title, :description)');
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
        ]);
    }

    public function getTaskById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

