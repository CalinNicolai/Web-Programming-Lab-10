<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <?php if (isset($event["name"])): ?>
        <h1>Event Registration: <?php echo $event["name"]; ?></h1>
        <p>Date: <?php echo $event["date"]; ?></p>
        <p>Number of seats: <?php echo $event["number_seats"]; ?></p>
        <p>Free seats: <?php echo $freeSeats; ?></p>
        <p>Price: <?php echo $event["price"]; ?></p>
        <hr>
    <?php endif; ?>
    <?php if (isset($recorded) && $recorded): ?>
        <div class="alert alert-success">
            You have successfully registered for the event
        </div>
    <?php elseif (isset($freeSeats) && $freeSeats <= 0): ?>
        <div class="alert alert-danger">
            Sorry, all seats are taken
        </div>
    <?php else: ?>
        <form method="post" action="/record_to_event">
            <input type="hidden" name="event_id" value="<?php echo isset($event["id"]) ? $event["id"] : ""; ?>">
            <button type="submit" name="submit" class="btn btn-primary">Record to event</button>
        </form>
    <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
