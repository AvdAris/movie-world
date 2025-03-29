<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Movie.php';

final class MovieController {
    private Movie $movieModel;

    public function __construct(Movie $movieModel) {
        $this->movieModel = $movieModel;
    }

    public function getMovies(): void {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            $sort = $_GET['sort'] ?? 'date';
            $direction = $_GET['direction'] ?? 'desc';

            $movies = $this->movieModel->getAllMovies((int)$userId, $sort, $direction);
            $this->sendResponse(200, $movies);
        } catch (Exception $e) {
            $this->sendResponse(500, ["error" => $e->getMessage()]);
        }
    }

    public function addMovie(): void {

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['title']) || !isset($data['description'])) {
            $this->sendResponse(400, ["success" => false, "error" => "Missing title or description"]);
            return;
        }

        try {
            error_log("SESSION: " . print_r($_SESSION, true));
            $success = $this->movieModel->addMovie($data['title'], $data['description'], (int)$_SESSION['user_id']);
            if ($success) {
                $this->sendResponse(201, ["success" => true, "message" => "Movie added successfully"]);
            } else {
                $this->sendResponse(500, ["success" => false, "error" => "Failed to add movie"]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ["error" => "Database Error: " . $e->getMessage()]);
        }
    }

    private function sendResponse(int $status, array $data): void {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
