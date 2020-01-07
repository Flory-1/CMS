<?php
// Call the CMS System
$CT = new CMS(
    [
        "events_check"=>true,
        "view"=>'week_view',
        "theme"=>[
            "theme"=>'Metro'
        ],
        "actions_form"=>[
            "active"=>true,
            "google"=>true
        ],
        "time_format"=>'H.i',
        "date_format"=>'d.m.Y',
        "is_book_able"=>false,
        "weekend_check"=>true
    ]
);
?>