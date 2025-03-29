<?php
declare(strict_types=1);

final class Vote {
    private PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function toggleVote(int $userId, int $movieId, string $voteType): string {
        $ownerId = $this->getMovieOwner($movieId);
        if ($ownerId === $userId) {
            throw new Exception("You cannot vote on your own movie.");
        }

        $existingVote = $this->getExistingVote($userId, $movieId);

        if (!$existingVote) {
            $this->addVote($userId, $movieId, $voteType);
            return "Vote added";
        }

        if ($existingVote === $voteType) {
            $this->removeVote($userId, $movieId);
            return "Vote removed";
        }

        $this->updateVote($userId, $movieId, $voteType);
        return "Vote updated";
    }


    private function getMovieOwner(int $movieId): int {
        $stmt = $this->db->prepare("SELECT user_id FROM movies WHERE id = ?");
        $stmt->execute([$movieId]);
        return (int) $stmt->fetchColumn();
    }

    private function getExistingVote(int $userId, int $movieId): ?string {
        $stmt = $this->db->prepare("SELECT vote FROM votes WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$userId, $movieId]);
        return $stmt->fetchColumn() ?: null;
    }

    private function addVote(int $userId, int $movieId, string $vote): void {
        $stmt = $this->db->prepare("INSERT INTO votes (user_id, movie_id, vote) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $movieId, $vote]);
    }

    private function removeVote(int $userId, int $movieId): void {
        $stmt = $this->db->prepare("DELETE FROM votes WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$userId, $movieId]);
    }

    private function updateVote(int $userId, int $movieId, string $vote): void {
        $stmt = $this->db->prepare("UPDATE votes SET vote = ? WHERE user_id = ? AND movie_id = ?");
        $stmt->execute([$vote, $userId, $movieId]);
    }
}
