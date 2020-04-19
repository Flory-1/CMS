<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"en",
        "tooltip"=>true,
        "events_check"=>true,
        "hidden_months"=>'4',
        "time_format"=>'s:i:H',
        "date_format"=>'d-m-Y',
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
        ]
    ]
);
?>