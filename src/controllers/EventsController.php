<?php

namespace App\controllers;

use App\models\Event;
use App\models\Token;
use App\models\User;

class EventsController
{
    private $connect;
    private array $get;
    private array $post;

    /**
     * Constructor for EventsController
     *
     * @param mixed $connect The database connection
     * @param array $get The GET parameters
     * @param array $post The POST parameters
     */
    public function __construct($connect, array $get, array $post)
    {
        $this->connect = $connect;
        $this->get = $get;
        $this->post = $post;
    }

    /**
     * Displays the current events page
     */
    public function currentEventsPage(): void
    {
        $events = Event::getCurrentEvents($this->connect);
        include(__DIR__ . '/../view/currentEvents.php');
    }

    /**
     * Displays the event page for a specific event
     *
     * @param mixed $eventId The ID of the event
     */
    public function eventPage($eventId): void
    {
        if (isset($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
            $recorded = User::isRecordedToEvent($eventId, $userID, $this->connect);
        }
        $event = Event::getEventById($this->connect, $eventId);
        $freeSeats = Event::getFreeSeatsCount($eventId, $this->connect);
        include(__DIR__ . '/../view/recordToEvent.php');
    }

    /**
     * Displays the admin page
     */
    public function adminPage(): void
    {
        $userID = $_SESSION['user_id'];
        if (!Token::checkToken($this->connect, $userID)) {
            UserController::logoutStatic($this->connect);
        }
        $admin = new adminController($this->connect, $this->get, $this->post);
        $admin->adminPage();
    }

    /**
     * Adds a new event
     */
    public function addEvent(): void
    {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $price = $_POST['price'];
        $number_seats = $_POST['number_seats'];
        $event = new Event($name, $price, $number_seats, $date, $this->connect);
        $event->createEvent();
        header('Location: /admin');
    }

    /**
     * Updates an existing event
     */
    public function update(): void
    {
        $id = $_POST['event_id'];
        $name = $_POST['name'];
        $date = $_POST['date'];
        $price = $_POST['price'];
        $number_seats = $_POST['number_seats'];

        $event = new Event($name, $price, $number_seats, $date, $this->connect);
        $event->updateEvent($id);

        header('Location: /admin');
    }

    /**
     * Deletes an event
     */
    public function delete(): void
    {
        $id = $_POST['event_id'];
        Event::removeEvent($this->connect, $id);
        header('Location: /admin');
    }

    /**
     * Displays the edit event page
     *
     * @param mixed $id The ID of the event to edit
     */
    public function editEventPage(mixed $id): void
    {
        $event = Event::getEventById($this->connect, $id);
        include(__DIR__ . '/../view/editEvent.php');
    }
}