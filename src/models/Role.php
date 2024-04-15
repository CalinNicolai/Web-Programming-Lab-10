<?php

namespace App\models;

use PDO;

class Role
{
    /**
     * @var int The id of the role
     */
    public int $id;

    /**
     * @var string The name of the role
     */
    public string $name;

    /**
     * Role constructor.
     * @param int $id The id of the role
     * @param string $name The name of the role
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Find a role by name
     * @param string $name The name of the role to search for
     * @param PDO $connect The database connection
     * @return Role|null The found role or null if not found
     */
    public static function findByName(string $name, PDO $connect): ?Role
    {
        $query = "SELECT * FROM roles WHERE name = :name";
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $result = $stmt->fetch();

        if (!$result) {
            return null; // Role not found
        }

        return new Role($result['id'], $result['name']);
    }

    /**
     * Find a role by id
     * @param int $id The id of the role to search for
     * @param PDO $connect The database connection
     * @return Role|null The found role or null if not found
     */
    public static function findById(int $id, PDO $connect): ?Role
    {
        $query = "SELECT * FROM roles WHERE id = :id";
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        if (!$result) {
            return null; // Role not found
        }

        return new Role($result['id'], $result['name']);
    }

    /**
     * Get the SQL query to create the 'roles' table
     * @return string The SQL query to create the table
     */
    public static function getCreateTableQuery(): string
    {
        return "CREATE TABLE IF NOT EXISTS roles (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
        );";
    }

}
