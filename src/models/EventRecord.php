<?php

namespace App\models;

use PDO;

/**
 * Class EventRecord
 * Represents an event record entity
 */
class EventRecord
{
    /**
     * @var int The ID of the event record
     */
    public int $id;

    /**
     * @var string The user associated with the event record
     */
    public string $user;

    /**
     * @var string The event associated with the event record
     */
    public string $event;

    /**
     * @var PDO The database connection
     */
    public PDO $connect;

    /**
     * EventRecord constructor.
     *
     * @param string $user The user associated with the event record
     * @param string $event The event associated with the event record
     * @param PDO $connect The database connection
     * @param int $id The ID of the event record (default is 0)
     */
    public function __construct(string $user, string $event, PDO $connect, int $id = 0)
    {
        $this->id = $id;
        $this->user = $user;
        $this->event = $event;
        $this->connect = $connect;
    }

    /**
     * Creates an event record in the database
     */
    public function createEventRecord(): void
    {
        $query = "INSERT INTO event_records (user_id, event_id) VALUES (:user_id, :event_id)";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':user_id', $this->user);
        $stmt->bindParam(':event_id', $this->event);
        $stmt->execute();
    }

    /**
     * Get the SQL query to create the event_records table if it doesn't exist
     *
     * @return string The SQL query
     */
    public static function getCreateTableQuery(): string
    {
        return "CREATE TABLE IF NOT EXISTS event_records (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            event_id INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (event_id) REFERENCES events(id)
            );";
    }
}