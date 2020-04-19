<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"de",
        "weekend_check"=>true,
        "theme"=>[
            "theme"=>'Test',
            "custom_url"=>'test/theme.json'
        ],
        "view"=>'year_view',
        "events_check"=>true,
        "date_format"=>'d-m.Y',
        "static_infos"=>[
            'active'=>true,
            "theme"=>true
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
        ]
    ]
);
?>