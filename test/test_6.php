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
    "min_year"=>''.date("Y").'',
    "my_year"=>''.(date("Y") + 2).'',
    "prev_year_check"=>true,
    "next_year_check"=>true,
    "url"=>'?test&id=6',
    "today_check"=>true,
    "my_months"=>["June", "July", "August"]
    ]
);
?>