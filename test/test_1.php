<?php
// Call the CMS System
$CT = new CMS(
    [
        "tooltip"=>true,
        "events_check"=>true,
        "header"=>[
            "left"=>[
                "legend"
            ],
            "center"=>[
                "prev_year",
                "next_year"
            ],
            "right"=>[
                "week_view",
                "year_view",
                "day_view",
                "list_view"
            ]
        ],
        "time_format"=>'H.i',
        "database_check"=>false,
        "error_log"=>true,
        "cur_year"=>'2020',
        "my_events"=>[
            [
                "id"=>'1',
                "event_name"=>"Test",
                "start_date"=>"2019-12-16",
                "end_date"=>"2019-12-26",
                "my_description"=>"Test array 1",
                "test_event"=>"0",
                "event_code"=>"meoghetnghgwtb",
                "email"=>"info@test.de",
                "firstname"=>"Florian",
                "lastname"=>"Lämmlein"
            ],
            [
                "id"=>'2',
                "event_name"=>"Test_2",
                "start_date"=>"2020-01-02 07:30:00",
                "end_date"=>"2020-01-03 12:00:00",
                "my_description"=>"Test the views and Datetime",
                "test_event"=>"0",
                "event_code"=>"gkrgmoejmohreh",
                "email"=>"info@test.de",
                "firstname"=>"Max",
                "lastname"=>"Mustermann"
            ]
        ],
        "date_format"=>"d.m.Y",
        "event_form"=>[
            'active'=>true
        ],
        "static_infos"=>[
                "active"=>true,
                "events"=>true,
                "version"=>true,
                "authors"=>true,
                "language"=>true,
                "theme"=>true
        ]
    ]
);
?>