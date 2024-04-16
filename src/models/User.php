<?php

namespace App\models;

use Exception;
use PDO;

/**
 * Class User
 * Represents a user in the system
 */
class User
{
    public int $id;
    public string $name;
    public string $surname;
    public string $email;
    public string $role;

    /**
     * Database connection object
     */
    public PDO $connect;

    /**
     * User constructor.
     *
     * @param string $name The user's name
     * @param string $surname The user's surname
     * @param string $email The user's email
     * @param PDO $connect The database connection object
     * @param int $id The user's ID (default is 0)
     * @param string $role The user's role (default is "user")
     */
    public function __construct(string $name, string $surname, string $email, PDO $connect, int $id = 0, string $role = "user")
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->role = $role;
        $this->connect = $connect;
    }

    /**
     * Creates a new user with the given password
     *
     * @param string $password The user's password
     *
     * @throws Exception if the email is invalid, already exists, or if the password requirements are not met
     */
    public function createUser(string $password): void
    {
        try {
            // Check if the email format is valid
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            // Check if the email is unique
            $query = "SELECT * FROM users WHERE email=?";
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$this->email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                throw new Exception("Email already exists");
            }

            // Check the password length and presence of a digit
            if (strlen($password) < 6 || strlen($password) > 30 || !preg_match('/\d/', $password)) {
                throw new Exception("Password must be between 6 and 30 characters long and contain at least one digit");
            }

            // Insert user data into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $roleId = Role::findByName($this->role, $this->connect);
            $query = "INSERT INTO users (name, surname, email, password, role_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$this->name, $this->surname, $this->email, $hashed_password, $roleId->id]);
            if (!$stmt) {
                throw new Exception("Error adding user: " . $stmt->errorInfo());
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage() . "\n"); // Log the error to a file
            echo "An error occurred. Please try again later."; // User message
        }
    }

    /**
     * Find a user by their ID
     *
     * @param int $id The user's ID
     * @param PDO $connection The database connection object
     *
     * @return array|null An array representing the user if found, null otherwise
     */
    public static function findById(int $id, PDO $connection): ?array
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null; // User with the specified ID not found
        }

        return $user;
    }

    /**
     * Retrieve all users from the database
     *
     * @param PDO $connection The database connection object
     *
     * @return array|false An array of all users, or false if no users found
     */
    public static function getAll(PDO $connection): array|false
    {
        $query = "SELECT * FROM users";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$users) {
            return false; // No users found
        }

        return $users;
    }


    /**
     * Check if the user already exists in the database
     *
     * @return bool True if the user exists, false otherwise
     */
    public function checkExistence(): bool
    {
        try {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->connect->prepare($query);
            $stmt->execute([$this->email]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error in Check Existence: " . $e->getMessage() . "\n");
            return false;
        }

    }

    /**
     * Find a user by their email
     *
     * @param string $email The user's email
     * @param PDO $connection The database connection object
     *
     * @return array|null An array representing the user if found, null otherwise
     */
    public static function findByEmail(string $email, PDO $connection): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $connection->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null; // User with the specified email not found
        }

        return $user;
    }

    /**
     * Retrieve events for a specific user from the database
     *
     * @param int $userId The ID of the user
     * @param PDO $connect The database connection
     * @return array|false Array of events for the user, or false if no events found
     */
    public static function getEventsForUser(int $userId, PDO $connect): array|false
    {
        // SQL query to retrieve events for the user
        $query = "
    SELECT e.id, e.name, e.date, e.price, e.number_seats
    FROM events e
    JOIN event_records er ON e.id = er.event_id
    JOIN users u ON er.user_id = u.id
    WHERE u.id = :userId
";

        // Prepare and execute the SQL query
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If no events found, return false
        if (!$result) {
            return false;
        }

        // Return the array of events
        return $result;
    }

    /**
     * Check if a user is recorded to a specific event.
     *
     * @param int $eventId The ID of the event to check against.
     * @param int $userID The ID of the user to check for.
     * @param PDO $connect The PDO database connection.
     * @return bool Returns true if the user is recorded to the event, false otherwise.
     */
    public static function isRecordedToEvent(int $eventId, int $userID, PDO $connect): bool
    {
        // SQL query to check if the user is recorded to the event
        $query = "
    SELECT 1
    FROM event_records er
    JOIN users u ON er.user_id = u.id
    WHERE u.id = :userId AND er.event_id = :eventId
";

        // Prepare and execute the SQL query
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':userId', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        // Return true if a row is found, false otherwise
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Get the SQL query to create the users table
     *
     * @return string The SQL query to create the users table
     */
    public static function getCreateTableQuery(): string
    {
        return "
            CREATE TABLE IF NOT EXISTS users
            (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            surname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role_id INT NOT NULL,
            FOREIGN KEY (role_id) REFERENCES roles(id)
            );";
    }
}
