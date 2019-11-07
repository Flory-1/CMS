<?php
// Call the CMS System
$CT = new CMS(
    [
    "lg"=>"en",
    "legend"=>true,
    "events_check"=>true,
    "min_days"=>'5',
    "static_infos"=>[
        "Authors", "Version"
    ],
    "event_form"=>[
        'active'=>true,
        'action'=>'?page_2',
        'modal'=>true
        ]
    ]
);
?>