<?php

declare(strict_types=1);

class Database {
    private PDO $connection;

    public function __construct(string $host, string $dbname, string $user, string $password) {
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
