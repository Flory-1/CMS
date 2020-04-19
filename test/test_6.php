<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"en",
        "tooltip"=>true,
        "events_check"=>true,
        "status_logs"=>true,
        "event_form"=>[
            'active'=>true,
            'action'=>'?page_2'
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
        "header"=>[
            "center"=>[
                "legend"
            ],
            "right"=>[
                "prev_year",
                "next_year",
                "today"
            ],
            "url"=>'?test&id=6'
        ],
        "min_year"=>''.date("Y").'',
        "max_year"=>''.(date("Y") + 2).'',
        "my_months"=>[
            "June",
            "July",
            "August"
        ]
    ]
);
?>