<div class="container mt-5">
    <h1>User Profile</h1>
    <h2>Events Registered:</h2>
    <div class="row mt-3">
        <?php foreach ($events as $event): ?>
            <?php
            $eventDate = strtotime($event['date']);
            $currentDate = time();
            $eventClass = ($eventDate < $currentDate) ? 'bg-danger text-white' : '';
            ?>
            <div class="col-md-4">
                <div class="card <?php echo $eventClass; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><a href="/event/<?php echo $event["id"]; ?>"><?php echo $event["name"]; ?></a></h5>
                        <p class="card-text">Date: <?php echo $event["date"]; ?></p>
                        <p class="card-text">Number of seats: <?php echo $event["number_seats"]; ?></p>
                        <p class="card-text">Price: <?php echo $event["price"]; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>