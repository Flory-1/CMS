<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"de",
        "events_check"=>true,
        "event_form"=>[
            'active'=>true,
            'action'=>'?page_2',
            "active_event"=>"1",
            "events"=>[
                '1' => "test",
                '2' => "gre",
                '6' => "frwea"
            ]
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
        "theme"=>[
            "theme"=>'Metro'
        ]
    ]
);
?>