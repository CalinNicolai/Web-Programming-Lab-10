<?php

namespace App\models;

use PDO;

class Event
{
    /**
     * @var int $id The ID of the event
     */
    public int $id;

    /**
     * @var string $name The name of the event
     */
    public string $name;

    /**
     * @var float $price The price of the event
     */
    public float $price;

    /**
     * @var int $number_seats The number of seats available for the event
     */
    public int $number_seats;

    /**
     * @var string $date The date of the event
     */
    public string $date;

    /**
     * @var PDO $connection The database connection
     */
    public PDO $connection;

    /**
     * Event constructor.
     * @param string $name The name of the event
     * @param float $price The price of the event
     * @param int $number_seats The number of seats available for the event
     * @param string $date The date of the event
     * @param PDO $connection The database connection
     * @param int $id The ID of the event (default is 0 for new events)
     */
    public function __construct(string $name, float $price, int $number_seats, string $date, PDO $connection, int $id = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->number_seats = $number_seats;
        $this->date = $date;
        $this->connection = $connection;
    }

    /**
     * Get all events from the database
     * @param PDO $connection The database connection
     * @return array An array of all events
     */
    public static function getAllEvents(PDO $connection): array
    {
        $sql = "SELECT * FROM events";
        $stmt = $connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get current events based on the current date
     * @param PDO $connection The database connection
     * @return array An array of current events
     */
    public static function getCurrentEvents(PDO $connection): array
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM events WHERE date > ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$currentDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get an event by its ID
     * @param PDO $connection The database connection
     * @param int $id The ID of the event
     * @return array|null The event data or null if not found
     */
    public static function getEventById(PDO $connection, int $id): ?array
    {
        $sql = "SELECT * FROM events WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new event
     * @return bool True if the event is created successfully, false otherwise
     */
    public function createEvent(): bool
    {
        $sql = "INSERT INTO events (name, price, number_seats, date) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([$this->name, $this->price, $this->number_seats, $this->date]);
    }

    /**
     * Update an existing event
     * @return bool True if the event is updated successfully, false otherwise
     */
    public function updateEvent(): bool
    {
        $sql = "UPDATE events SET name = ?, price = ?, number_seats = ?, date = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([$this->name, $this->price, $this->number_seats, $this->date, $this->id]);
    }

    /**
     * Remove an event by its ID
     * @param PDO $connection The database connection
     * @param int $eventId The ID of the event to remove
     * @return bool True if the event is removed successfully, false otherwise
     */
    public static function removeEvent(PDO $connection, int $eventId): bool
    {
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $connection->prepare($sql);
        return $stmt->execute([$eventId]);
    }
    /**
     * Get the count of available free seats for a specific event.
     *
     * @param int $eventId The ID of the event
     * @param PDO $connection The database connection
     * @return int The number of available free seats
     */
    public static function getFreeSeatsCount(int $eventId, PDO $connection): int
    {
        // SQL query to calculate the free seats for a specific event
        $sql = "
    SELECT number_seats - COUNT(er.id) as free_seats
    FROM events e
    LEFT JOIN event_records er ON e.id = er.event_id
    WHERE e.id = ?
    GROUP BY e.id
";

        // Prepare and execute the SQL query
        $stmt = $connection->prepare($sql);
        $stmt->execute([$eventId]);

        // Fetch the result and return the count of free seats
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['free_seats'])) {
            return (int)$result['free_seats'];
        }

        // If no result or free seats count is not set, return 0
        return 0;
    }


    /**
     * Returns the SQL query to create the events table if it doesn't exist.
     *
     * @return string
     */
    public static function getCreateTableQuery(): string
    {
        return "CREATE TABLE IF NOT EXISTS events (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    number_seats INT NOT NULL,
    date DATETIME NOT NULL
);";
    }

}




