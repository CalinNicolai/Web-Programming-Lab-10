<?php

namespace Store\App\router;

use App\controllers\EventsController;
use App\controllers\UserController;
use App\router\Router;

// Define routes for GET requests
Router::addRoute('GET', '/', function () use ($conn) {
    // Handle request to display current events page
    $events = new EventsController($conn, $_GET, $_POST);
    $events->currentEventsPage();
});

Router::addRoute('GET', '/event/{id}', function ($id) use ($conn) {
    // Handle request to display a specific event page
    $events = new EventsController($conn, $_GET, $_POST);
    $events->eventPage($id["id"]);
});

Router::addRoute('GET', '/login', function () use ($conn) {
    // Handle request to display login page
    $user = new UserController($conn, $_GET, $_POST);
    $user->getLogin();
});

Router::addRoute('GET', '/registration', function () use ($conn) {
    // Handle request to display registration page
    $user = new UserController($conn, $_GET, $_POST);
    $user->getRegister();
});

Router::addRoute('GET', '/admin', function () use ($conn) {
    // Handle request to display admin page
    $events = new EventsController($conn, $_GET, $_POST);
    $events->adminPage();
});

Router::addRoute('GET', '/logout', function () use ($conn) {
    // Handle request to logout
    $user = new UserController($conn, $_GET, $_POST);
    $user->logout();
});

Router::addRoute('GET', '/profile', function () use ($conn) {
    // Handle request to display user profile
    $user = new UserController($conn, $_GET, $_POST);
    $user->userInfo();
});

Router::addRoute('GET', '/edit_event/{id}', function ($id) use ($conn) {
    // Handle request to display edit event page
    $events = new EventsController($conn, $_GET, $_POST);
    $events->editEventPage($id["id"]);
});

Router::addRoute('GET', '/admin/users', function () use ($conn) {
    // Handle request to edit an event
    $events = new EventsController($conn, $_GET, $_POST);
    $events->adminUsersPage();
});

Router::addRoute('GET', '/admin/user/{id}', function ($id) use ($conn) {
    // Handle request to edit an event
    $events = new EventsController($conn, $_GET, $_POST);
    $events->adminUserInfoPage($id["id"]);
});

// Define routes for POST requests
Router::addRoute('POST', '/login', function () use ($conn) {
    // Handle login request
    $user = new UserController($conn, $_GET, $_POST);
    $user->Login();
});

Router::addRoute('POST', '/registration', function () use ($conn) {
    // Handle registration request
    $user = new UserController($conn, $_GET, $_POST);
    $user->Registration();
});

Router::addRoute('POST', '/add_event', function () use ($conn) {
    // Handle request to add an event
    $event = new EventsController($conn, $_GET, $_POST);
    $event->addEvent();
});

Router::addRoute('POST', '/delete', function () use ($conn) {
    // Handle request to delete an event
    $event = new EventsController($conn, $_GET, $_POST);
    $event->delete();
});

Router::addRoute('POST', '/update_event', function () use ($conn) {
    // Handle request to update an event
    $event = new EventsController($conn, $_GET, $_POST);
    $event->update();
});

Router::addRoute('POST', '/record_to_event', function () use ($conn) {
    // Handle request to record attendance to an event
    $user = new UserController($conn, $_GET, $_POST);
    $user->recordToEvent();
});
