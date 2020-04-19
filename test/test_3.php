<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"en",
        "events_check"=>true,
        "hidden_months"=>'1',
        "auto_size"=>true,
        "static_infos"=>[
            "active"=>true,
            "authors"=>true,
            "version"=>true,
            "language"=>true
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
        "event_form"=>[
            'active'=>true,
            'action'=>'my_page.php',
            "modal"=>true
        ],
        "tooltip"=>true,
        "weekend_check"=>true,
        "theme"=>[
            "theme"=>'Jquery'
        ]
    ]
);
?>