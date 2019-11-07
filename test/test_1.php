<?php
// Call the CMS System
$CT = new CMS(
    [
    "tooltip"=>true,
    "events_check"=>true,
    "theme"=>"Horizon",
    "time_format"=>"H.i",
    "database_check"=>false,
    "error_log"=>true,
    "export"=>true,
    "my_events"=>[
        [
            "event_name"=>"Test",
            "start_date"=>"2019-10-22",
            "end_date"=>"2019-10-26",
            "my_description"=>"Test array 1",
            "status"=>"3",
            "test_event"=>"0",
            "event_code"=>"meoghetnghgwtb",
            "email"=>"info@test.de",
            "firstname"=>"Florian",
            "lastname"=>"Lämmlein"
        ],
        [
            "event_name"=>"Test_2",
            "start_date"=>"2019-11-03",
            "end_date"=>"2019-11-12",
            "my_description"=>"Test array 2",
            "status"=>"2",
            "test_event"=>"0",
            "event_code"=>"gkrgmoejmohreh",
            "email"=>"info@test.de",
            "firstname"=>"Max",
            "lastname"=>"Mustermann"
        ]
    ],
    "date_format"=>"d.m.Y",
    "event_form"=>['active'=>true],
    "static_infos"=>[
            "Events", "Version", "Authors", "Language", "Theme"
        ],
    "tooltip_functions"=>[
            "firstname", "lastname"
        ],
    "legend"=>true
    ]
);
?>