<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <h1>Edit Event</h1>
    <form action="/update_event" method="post">
        <input type="hidden" name="event_id" value="<?php echo $event["id"]?>" >
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $event["name"]; ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo substr($event["date"], 0, 10); ?>" required>
        </div>
        <div class="mb-3">
            <label for="number_seats" class="form-label">Number of Seats</label>
            <input type="number" class="form-control" id="number_seats" name="number_seats" value="<?php echo $event["number_seats"]; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $event["price"]; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
