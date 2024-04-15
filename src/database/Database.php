<?php

namespace App\database;

use App\models\Capability;
use App\models\Event;
use App\models\EventRecord;
use App\models\Role;
use App\models\RolesCapabilities;
use App\models\Token;
use App\models\User;
use PDO;
use PDOException;

/**
 * Class Database
 * Handles database connections and operations
 */
class Database
{
    const string DB_HOST = 'localhost';
    const string DB_USER = 'root';
    const string DB_PASSWORD = 'root';
    const string DB_NAME = 'event_platform';

    /**
     * Connects to the database and returns the connection object
     *
     * @return PDO The database connection object
     */
    public static function connect(): PDO
    {
        // Database credentials
        $host = self::DB_HOST;
        $dbname = self::DB_NAME;
        $username = self::DB_USER;
        $password = self::DB_PASSWORD;

        // Connection string
        $dsn = "mysql:host=$host;dbname=$dbname";

        try {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $username, $password);

            // Set PDO to throw exceptions on errors
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            error_log("Error connect DataBase: " . $e->getMessage());
            die("Error connecting DataBase");
        }
    }

    /**
     * Builds the necessary database tables
     */
    public static function buildDatabase(): void
    {
        echo "Building Database...";
        try {
            // Connect to the MySQL database
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME;

            // Establish connection to the database
            $pdo = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create tables
            $query = Role::getCreateTableQuery();
            $query .= User::getCreateTableQuery();
            $query .= Token::getCreateTableQuery();
            $query .= Event::getCreateTableQuery();
            $query .= EventRecord::getCreateTableQuery();
            $query .= Capability::getCreateTableQuery();
            $query .= RolesCapabilities::getCreateTableQuery();
            $pdo->exec($query);
            echo "Database created successfully or already exists";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again later. {$e->getMessage()}";
        }
    }
}