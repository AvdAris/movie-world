<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/controllers/MovieController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/VoteController.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';



final class Router {
    private MovieController $movieController;
    private AuthController $authController;
    private VoteController $voteController;


    public function __construct(MovieController $movieController, AuthController $authController, VoteController $voteController) {
       $this->movieController = $movieController;
       $this->authController = $authController;
       $this->voteController = $voteController;
    }
    public function handleRequest() {    
        $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($request_uri) {
            case "/api/movies":
                if ($method === "GET") {
                    $this->movieController->getMovies();
                } elseif ($method === "POST") {
                    requireAuth();
                    $this->movieController->addMovie();
                } else {
                    $this->sendResponse(405, ["error" => "Method Not Allowed"]);
                }
                break;
            
            case "/api/auth/login":
                $this->authController->login();
                break;

            case "/api/auth/signup":
                $this->authController->signup();
                break;
            
            case "/api/auth/logout":
                session_destroy();
                header("Location: /");
                break;
            case "/api/votes":
                requireAuth();
                if ($method === "POST") {
                    $this->voteController->handleVote();
                } else {
                    $this->sendResponse(405, ["error" => "Method Not Allowed"]);
                }
                break;
            default:
                $this->sendResponse(404, ["error" => "Not Found"]);
        }
    }

    private function sendResponse($status, $data) {
        http_response_code($status);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
?>
