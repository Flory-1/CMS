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
        "event_form"=>[
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