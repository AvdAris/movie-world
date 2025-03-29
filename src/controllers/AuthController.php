<?php
require_once __DIR__ . '/../models/User.php';

final class AuthController {
    private $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }


    public function signup() {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (strlen($password) < 6) {
            return $this->json(["success" => false, "message" => "Password must be at least 6 characters."], 400);
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return $this->json(["success" => false, "message" => "Password must include at least one uppercase letter."], 400);
        }
    
        if (!preg_match('/[a-z]/', $password)) {
            return $this->json(["success" => false, "message" => "Password must include at least one lowercase letter."], 400);
        }
    
        if (!preg_match('/[0-9]/', $password)) {
            return $this->json(["success" => false, "message" => "Password must include at least one number."], 400);
        }
    
        if (!preg_match('/[\W]/', $password)) {
            return $this->json(["success" => false, "message" => "Password must include at least one special character."], 400);
        }

        if (!$username || !$password) {
            return $this->json(["success" => false, "message" => "Username and password required."], 400);
        }

        if ($this->userModel->findByUsername($username)) {
            return $this->json(["success" => false, "message" => "Username already taken."], 409);
        }

        $userId = $this->userModel->create($username, $password);
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userId;
        $this->json(["success" => true, "message" => "Signup successful."]);
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        $user = $this->userModel->findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->json(["success" => false, "message" => "Invalid credentials."], 401);
        }

        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        $this->json(["success" => true, "message" => "Login successful."]);
    }

    public function logout() {
        session_destroy();
        $this->json(["success" => true, "message" => "Logged out."]);
    }

    private function json($data, $code = 200) {
        http_response_code($code);
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
