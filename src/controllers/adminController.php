<?php

namespace App\controllers;

use App\models\Event;
use App\models\Role;
use App\models\User;
use PDO;

/**
 * Class adminController
 *
 * This class handles the admin-related functionalities
 */
class adminController
{
    private PDO $connect;
    private array $get;
    private array $post;

    /**
     * adminController constructor.
     *
     * @param PDO $connect The database connection
     * @param array $get The GET parameters
     * @param array $post The POST parameters
     */
    public function __construct(PDO $connect, array $get, array $post)
    {
        $this->connect = $connect;
        $this->get = $get;
        $this->post = $post;
    }

    /**
     * Render the admin page if the user is an admin
     */
    public function adminPage(): void
    {
        if (isset($_SESSION['user_role_id'])) {
            $role = Role::findByID($_SESSION['user_role_id'], $this->connect)->name;
            if ($role !== 'admin') {
                header('Location: /');
            }
        }

        // Get all events from the database
        $events = Event::getAllEvents($this->connect);

        // Include the admin view file
        include(__DIR__ . '/../view/admin.php');
    }

    /**
     * Render the admin users page if the user is an admin
     */
    public function adminUsersPage(): void
    {
        if (isset($_SESSION['user_role_id'])) {
            $role = Role::findByID($_SESSION['user_role_id'], $this->connect)->name;
            if ($role !== 'admin') {
                header('Location: /');
            }
        }

        // Get all users from the database
        $users = User::getAll($this->connect);

        // Include the admin view file
        include(__DIR__ . '/../view/adminUsers.php');
    }

    public function adminUserInfoPage($id): void
    {
        if (isset($_SESSION['user_role_id'])) {
            $role = Role::findByID($_SESSION['user_role_id'], $this->connect)->name;
            if ($role !== 'admin') {
                header('Location: /');
            }
        }

        // Get all users from the database
        $userID = $id;
        $user = User::findByID($userID, $this->connect);
        $events = User::getEventsForUser($userID, $this->connect);
        // Include the admin view file
        include(__DIR__ . '/../view/adminUserInfo.php');
    }
}