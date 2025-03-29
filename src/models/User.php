<?php

final class User {
    private PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE name = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(string $username, string $password): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => $hash
        ]);
        return (int)$this->db->lastInsertId();
    }
}
