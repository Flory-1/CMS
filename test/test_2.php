<?php
// Call the CMS System
$CT = new CMS(
    [
        "events_check"=>true,
        "view"=>'day_view',
        "theme"=>[
            "theme"=>'Metro'
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
        "actions_form"=>[
            "active"=>true,
            "google"=>true
        ],
        "time_format"=>'H.i',
        "date_format"=>'d.m.Y',
        "is_book_able"=>false,
        "weekend_check"=>true,
        "time_split"=>5
    ]
);
?>