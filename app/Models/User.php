<?php

class User {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $sql = "INSERT INTO users
        (firstname, lastname, email, password_hash, role, department, position, phone, photo, cohort)
        VALUES
        (:firstname, :lastname, :email, :password_hash, :role, :department, :position, :phone, :photo, :cohort)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}