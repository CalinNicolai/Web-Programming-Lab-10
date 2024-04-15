<?php

namespace App\models;

use mysqli;

class Capability
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * Capability constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Find capability by id
     * @param $id
     * @param mysqli $connect
     * @return array|null
     */
    public function findCapabilityById($id, mysqli $connect): ?array
    {
        $query = "SELECT * FROM capabilities WHERE id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : null;
    }

    /**
     * Get create capabilities table query
     * @return string
     */
    public static function getCreateTableQuery(): string
    {
        return "
        CREATE TABLE IF NOT EXISTS capabilities (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";
    }

}