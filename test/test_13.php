<?php
// Call the CMS System
$CT = new CMS(
    [
        "events_check"=>true,
        "view"=>'week_vie',
        "theme"=>[
            "theme"=>'Jquer'
        ],
        "actions_form"=>[
            "active"=>true,
            "google"=>true
        ],
        "is_book_able"=>true,
        "weekend_chec"=>true,
        "time_split"=>15
    ]
);
?>