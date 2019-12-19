<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"de",
        "events_check"=>true,
        "event_form"=>[
            'active'=>true,
            'action'=>'?page_2',
            'person'=>true,
            'payment'=>true,
            "active_event"=>['1', '2', '6']
        ],
        "theme"=>[
            "theme"=>'Metro'
        ]
    ]
);
?>