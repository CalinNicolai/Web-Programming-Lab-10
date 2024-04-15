<?php

namespace App\models;

use PDO;

/**
 * Class Token
 * @package App\models
 */
class Token
{
    /**
     * Secret key for token generation
     */
    const string SECRET_KEY = 'I LOVE PHP';

    /**
     * @var int Token ID
     */
    public int $id;

    /**
     * @var string Generated token
     */
    public string $token;

    /**
     * @var array User associated with the token
     */
    public array $user;

    /**
     * @var PDO Database connection
     */
    public PDO $connect;

    /**
     * Token constructor.
     * @param int $id Token ID
     * @param $user
     * @param PDO $connect Database connection
     */
    public function __construct(int $id, $user, PDO $connect)
    {
        $this->id = $id;
        $this->user = $user;
        $this->connect = $connect;

// Token Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

// Payload with user data
        $payload = [
            'user_id' => $user['id'],
            'name' => $user['name'],
            'surname' => $user['surname'],
            'email' => $user['email'],
            'time' => time()
        ];

// Encode header and payload to base64 and concatenate with a dot
        $base64UrlHeader = base64_encode(json_encode($header));
        $base64UrlPayload = base64_encode(json_encode($payload));
        $token = $base64UrlHeader . '.' . $base64UrlPayload;

// Sign the token using the secret key
        $signature = hash_hmac('sha256', $token, self::SECRET_KEY, true);
        $base64UrlSignature = base64_encode($signature);

// Add the signature to the token
        $this->token = $token . '.' . $base64UrlSignature;
    }

    /**
     * Find tokens by user ID
     * @param PDO $connect Database connection
     * @param int $userId User ID
     * @return array|null Array of tokens or null if not found
     */
    public static function findByUserId(PDO $connect, int $userId): ?array
    {
        $query = "SELECT * FROM tokens WHERE user_id = :user_id";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: null;
    }


    /**
     * Find a record in the tokens table by token.
     *
     * @param PDO $connect The database connection
     * @param string $token The token to search for
     * @return array|null The record found or null if not found
     */
    public static function findByToken(PDO $connect, string $token): ?array
    {
        $query = "SELECT id FROM tokens WHERE token = :token";
        $stmt = $connect->prepare($query);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }


    /**
     * Create a token entry in the database
     * @return bool True if token creation is successful, false otherwise
     */
    public function createToken(): bool
    {
        $query = "INSERT INTO tokens (token, user_id) VALUES (:token, :user_id)";
        $stmt = $this->connect->prepare($query);
        $stmt->bindValue(':token', $this->token);
        $stmt->bindValue(':user_id', $this->user['id'], PDO::PARAM_INT);
        return $stmt->execute();
    }


    /**
     * Get the SQL query for creating the tokens table
     * @return string SQL query for creating the tokens table
     */
    public static function getCreateTableQuery(): string
    {
        return "
        CREATE TABLE IF NOT EXISTS tokens (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(255) NOT NULL,
            user_id INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
        ";
    }

    /**
     * Check if the token in the cookie matches the token in the database for a given user.
     *
     * @param PDO $connect The PDO database connection object
     * @param int $userId The user ID to check the token for
     * @return bool Returns true if the tokens match, false otherwise
     */
    public static function checkToken(PDO $connect, int $userId): bool
    {
        // Check if the token cookie is set
        if (!isset($_COOKIE['token'])) {
            return false;
        }

        // Get the token from the cookie
        $cookieToken = $_COOKIE['token'];

        // Get the token from the database for the user
        $dbToken = self::findByUserId($connect, $userId)['0']['token'];

        // Compare the tokens
        return $cookieToken === $dbToken;
    }

    /**
     * Delete a token from the tokens table.
     *
     * @param string $token The token to be deleted
     * @param PDO $connect The database connection
     *
     * @return bool True if the token was successfully deleted, false otherwise
     */
    public static function deleteToken(string $token, PDO $connect): bool
    {
        // Prepare the SQL query
        $query = "DELETE FROM tokens WHERE token = :token";
        $stmt = $connect->prepare($query);

        // Bind the token parameter
        $stmt->bindValue(':token', $token);

        // Execute the query and return the result
        return $stmt->execute();
    }
}