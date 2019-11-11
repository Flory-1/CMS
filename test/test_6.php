<?php
// Call the CMS System
$CT = new CMS(
    [
    "lg"=>"en",
    "legend"=>true,
    "tooltip"=>true,
    "events_check"=>true,
    "error_log"=>true,
    "event_form"=>[
        'active'=>true,
        'action'=>'?page_2'
    ],
    "buttons"=>[
        "active"=>true,
        "prev_year"=>true,
        "next_year"=>true,
        "today"=>true,
        "url"=>'?test&id=6'
    ],
    "min_year"=>''.date("Y").'',
    "max_year"=>''.(date("Y") + 2).'',
    "my_months"=>["June", "July", "August"]
    ]
);
?>