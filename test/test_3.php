<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"en",
        "events_check"=>true,
        "static_infos"=>[
            "active"=>true,
            "authors"=>true,
            "version"=>true,
            "language"=>true
        ],
        "event_form"=>[
            'active'=>true,
            'action'=>'my_page.php',
            "modal"=>true
        ],
        "tooltip"=>true
    ]
);
?>