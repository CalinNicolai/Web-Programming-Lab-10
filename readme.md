# Отчет по лабораторной работе #10

## Инструкция по запуску

1. Клонируйте репозиторий

```bash 
git clone https://github.com/CalinNicolai/Web-Programming-Lab-8   
```

2. Откройте проект в вашей среде разработки (например, PhpStorm).
3. Откройте docker Desktop
4. Поднимите базу данных например MariaDB. Для этого выполните команды

```bash
cd .\MariaDB\
```
```bash
docker-compose up -d
```

5. Перейдите по ссылке [localhost:8080](localhost:8080)
6. Создайте базу данных event_platform
7. Импортируйте настройки базы данных MySQL из папки `importDB`
8. Запустите проекта командой

```bash
php -S localhost:8000 -t public
```

## Описание лабораторной работы

В данной лабораторной работе нужно разработать веб-приложение для платформы проведения городских мероприятий. Платформа
должна позволять гражданам узнавать и записываться на мероприятия.

## Краткая документация к проекту

Router.php

```php
/**
 * Class Router - Responsible for routing requests to the appropriate handler
 */
class Router
{
    /**
     * @var array An array to store routes based on HTTP methods
     */
    public static array $routes = [];

    /**
     * Add a new route to the routing table
     *
     * @param string $method The HTTP method of the route (e.g., GET, POST)
     * @param string $pattern The URL pattern to match
     * @param callable $callback The callback function to execute when the route is matched
     */
    public static function addRoute(string $method, string $pattern, callable $callback): void
    {
        self::$routes[$method][$pattern] = $callback;
    }

    /**
     * Insert routes from an external file into the routing table
     *
     * @param mixed $conn The database connection (not used in the current implementation)
     */
    public static function insertRoutes(mixed $conn): void
    {
        require_once __DIR__ . "/routes.php";
    }

    /**
     * Handle an incoming request and route it to the appropriate callback function
     *
     * @param string $method The HTTP method of the request
     * @param string $url The URL of the request
     * @return mixed The result of the routed callback function or false if no route matches
     */
    public static function handleRequest(string $method, string $url): mixed
    {
        foreach (self::$routes[$method] as $pattern => $callback) {
            // Replace route parameters in the pattern with named capture groups
            $pattern = preg_replace('/{([^\/]+)}/', '(?P<\1>[^\/]+)', $pattern);
            if (preg_match("#^$pattern$#", $url, $matches)) {
                return call_user_func_array($callback, [$matches]);
            }
        }
        return false; // No matching route found
    }
}
```

Database.php

```php
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
```

## Вывод

В данной работе я научился работать с базой данных внутри проекта на языке PHP.