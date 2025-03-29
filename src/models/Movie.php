<?php

declare(strict_types=1);


final class Movie {
    private PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function getAllMovies(?int $userId, string $sortBy, string $direction): array {
        $validSorts = ['likes', 'hates', 'date'];
        $validDirections = ['asc', 'desc'];
        if (!in_array($sortBy, $validSorts)) {
            $sortBy = 'date';
        }

        if (!in_array(strtolower($direction), $validDirections)) {
            $direction = 'desc';
        }

        $orderBy = match ($sortBy) {
            'likes' => '(SELECT COUNT(*) FROM votes v WHERE v.movie_id = m.id AND v.vote = "like")',
            'hates' => '(SELECT COUNT(*) FROM votes v WHERE v.movie_id = m.id AND v.vote = "hate")',
            default => 'm.publication_date'
        };

        $query = "
            SELECT 
                `m`.`id`,
                `m`.`title`,
                `m`.`description`,
                `m`.`publication_date`,
                `m`.`user_id`,
                `u`.`name` AS `poster_name`,
                COUNT(CASE WHEN `v`.`vote` = 'like' THEN 1 END) AS `likes_count`,
                COUNT(CASE WHEN `v`.`vote` = 'hate' THEN 1 END) AS `hates_count`,
                MAX(CASE WHEN `v`.`user_id` = :userId THEN `v`.`vote` END) AS `user_vote`
            FROM `movies` `m`
            JOIN `users` `u` ON `m`.`user_id` = `u`.`id`
            LEFT JOIN `votes` `v` ON `v`.`movie_id` = `m`.`id`
            GROUP BY 
                `m`.`id`,
                `m`.`title`,
                `m`.`description`,
                `m`.`publication_date`,
                `m`.`user_id`,
                `u`.`name`
            ORDER BY $orderBy $direction;

        ";

        $stmt = $this->db->prepare($query);
        try{
            $stmt->execute(['userId' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Database Error: " . $e->getMessage());
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMovie(string $title, string $description, int $userId): bool {
        $query = "INSERT INTO movies (title, description, publication_date, user_id) VALUES (:title, :description, NOW(), :user_id)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute(['title' => $title, 'description' => $description, 'user_id' => $userId]);
    }
}
