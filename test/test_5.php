<?php
// Call the CMS System
$CT = new CMS(
    [
    "lg"=>"de",
    "legend"=>true,
    "back_days"=>true,
    "tooltip"=>true,
    "events_check"=>true,
    "static_infos"=>[
        "Events", "Author", "Version", "Language"
    ],
    "event_form"=>[
        'active'=>true,
        'action'=>'?page_2',
        'person_check'=>true,
        'payment_check'=>true,
        "active_event"=>'2'
    ],
    "min_year"=>'2000',
    "my_year"=>'2030',
    "prev_year_check"=>true,
    "next_year_check"=>true,
    "today_check"=>true,
    "theme"=>'Horizon'
    ]
);
?>