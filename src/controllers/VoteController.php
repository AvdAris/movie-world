<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Vote.php';

final class VoteController {
  private Vote $voteModel;

  public function __construct(Vote $voteModel) {
    $this->voteModel = $voteModel;
  }

  public function handleVote(): void {
    if (!isset($_SESSION['user_id'])) {
      $this->sendResponse(401, ["success" => false, "message" => "Unauthorized"]);
      return;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $_SESSION['user_id'];
    $movieId = (int)($data['movie_id'] ?? 0);
    $vote = $data['vote'] ?? '';

    if (!$movieId || !in_array($vote, ['like', 'hate'])) {
      $this->sendResponse(400, ["success" => false, "message" => "Invalid vote data"]);
      return;
    }

    try {
      $result = $this->voteModel->toggleVote($userId, $movieId, $vote);
      $this->sendResponse(200, ["success" => true, "message" => $result]);
    } catch (Exception $e) {
      $this->sendResponse(500, ["success" => false, "message" => $e->getMessage()]);
    }
  }

  private function sendResponse(int $status, array $data): void {
    http_response_code($status);
    header("Content-Type: application/json");
    echo json_encode($data, JSON_PRETTY_PRINT);
  }
}
