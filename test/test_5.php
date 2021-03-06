<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"de",
        "back_days"=>true,
        "tooltip"=>true,
        "events_check"=>true,
        "hidden_months"=>'3',
        "static_infos"=>[
            'active'=>true,
            'events'=>true,
            'version'=>true,
            'language'=>true
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
            'action'=>'?page_2',
            'active_event'=>'1',
            "events"=>[
                "IFA",
                "E3"
            ]
        ],
        "min_year"=>'2000',
        "max_year"=>'2030',
        "theme"=>[
            "theme"=>'Horizon'
        ],
        "header"=>[
            "left"=>[
                "legend"
            ],
            "center"=>[
                "prev_year",
                "next_year",
                "prev_month",
                "next_month",
                "today"
            ]
        ]
    ]
);
?>