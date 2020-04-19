<?php
// Call the CMS System
$CT = new CMS(
    [
        "events_check"=>true,
        "view"=>'week_view',
        "theme"=>[
            "theme"=>'Jquery'
        ],
        "actions_form"=>[
            "active"=>true,
            "google"=>true
        ],
        "my_events"=>[
            [
                "id"=>'1',
                "event_name"=>"Test Tooltip",
                "start_date"=>"2020-04-08",
                "end_date"=>"2020-04-09",
                "my_description"=>"Test Tooltip Functions",
                "test_event"=>"0",
                "email"=>"info@test.de",
                "firstname"=>"Florian",
                "lastname"=>"Lämmlein"
            ]
        ],
        "is_book_able"=>true,
        "weekend_check"=>true,
        "time_split"=>15
    ]
);
?>