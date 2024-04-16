<?php

namespace App\controllers;

use App\models\EventRecord;
use App\models\Token;
use App\models\User;
use PDO;

/**
 * Class UserController
 * @package App\controllers
 */
class UserController
{
    private PDO $connect;
    private array $get;
    private array $post;

    /**
     * UserController constructor.
     * @param PDO $connect Database connection
     * @param array $get GET parameters
     * @param array $post POST parameters
     */
    public function __construct(PDO $connect, array $get, array $post)
    {
        $this->connect = $connect;
        $this->get = $get;
        $this->post = $post;
    }

    /**
     * Display login form if user is not logged in
     */
    public function getLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
        }
        include(__DIR__ . '/../view/login.php');
    }

    /**
     * Display registration form if user is not logged in
     */
    public function getRegister(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
        }
        include(__DIR__ . '/../view/registration.php');
    }

    /**
     * Process user login
     */
    public function Login(): void
    {
        $email = $this->post['email'] ?? '';
        $password = $this->post['password'] ?? '';
        $user = User::findByEmail($email, $this->connect);

        if ($user && password_verify($password, $user['password'])) {
            $token = new Token($user['id'], $user, $this->connect);
            $token->createToken();
            setcookie('token', $token->token, time() + 3600, '/');
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role_id'] = $user['role_id'];
            header('Location: /');
        } else {
            header('Location: /login');
            error_log("Invalid email or password");
        }
    }

    /**
     * Process user registration
     * @throws \Exception
     */
    public function Registration(): void
    {
        $name = $this->post['name'];
        $surname = $this->post['surname'];
        $email = $this->post['email'];
        $password = $this->post['password'];
        $user = new User($name, $surname, $email, $this->connect);
        $user->createUser($password);
        header("/login");
    }

    /**
     * Log out the user
     */
    public function logout(): void
    {
        error_log("You are logged out");
        session_destroy();
        Token::deleteToken($_COOKIE['token'], $this->connect);
        setcookie('token', '', time() - 3600, '/');
        header('Location: /');
    }

    /**
     * Static method to log out the user
     * @param PDO $connection
     */
    public static function logoutStatic(PDO $connection): void
    {
        error_log("You are logged out");
        session_destroy();
        Token::deleteToken($_COOKIE['token'], $connection);
        setcookie('token', '', time() - 3600, '/');
        header('Location: /');
    }

    /**
     * Record an event associated with the user
     */
    public function recordToEvent(): void
    {
        $userID = $_SESSION['user_id'];
        if (!Token::checkToken($this->connect, $userID)) {
            self::logout();
        }
        $eventID = $this->post['event_id'];
        $eventRecord = new EventRecord($userID, $eventID, $this->connect);
        $eventRecord->createEventRecord();
        header('Location: /');
    }

    /**
     * Display user information
     */
    public function userInfo(): void
    {
        $userID = $_SESSION['user_id'];
        if (!Token::checkToken($this->connect, $userID)) {
            self::logout();
        }
        $events = User::getEventsForUser($userID, $this->connect);
        include(__DIR__ . '/../view/user.php');
    }
}