<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/models/Movie.php';
require_once __DIR__ . '/../src/controllers/MovieController.php';
require_once __DIR__ . '/Router.php';

$host = getenv('DB_HOST') ?: 'movieworld-mysql';
$dbname = getenv('DB_NAME') ?: 'movieworld';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_ROOT_PASS') ?: '';

$database = new Database($host, $dbname, $user, $password);

$movieModel = new Movie($database);
$userModel = new User($database);
$voteModel = new Vote($database);

$movieController = new MovieController($movieModel);
$authController = new AuthController($userModel);
$voteController = new VoteController($voteModel);
$router = new Router($movieController, $authController, $voteController);
$router->handleRequest();
