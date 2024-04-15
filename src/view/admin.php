<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Admin Panel</h1>
    <div class="row mt-3">
        <div class="col-md-12">
            <h3>Events</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Number of Seats</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($events as $event) { ?>
                    <tr>
                        <td><?php echo $event["name"]; ?></td>
                        <td><?php echo $event["date"]; ?></td>
                        <td><?php echo $event["number_seats"]; ?></td>
                        <td><?php echo $event["price"]; ?></td>
                        <td>
                            <form action="/edit_event/<?php echo $event["id"]; ?>" method="get">
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </form>

                            <form action="/delete" method="post">
                            <input type="hidden" name="event_id" value="<?php echo $event["id"] ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6">
            <h3>Add Event</h3>
            <form action="/add_event" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="number_seats" class="form-label">Number of Seats</label>
                    <input type="number" class="form-control" id="number_seats" name="number_seats" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Event</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
