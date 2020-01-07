<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"en",
        "events_check"=>true,
        "min_days"=>'5',
        "static_infos"=>[
            "active"=>true,
            "authors"=>true,
            "version"=>true
        ],
        "event_form"=>[
            'action'=>'?page_2',
            'modal'=>true
        ],
        "header"=>[
            "left"=>[
                "legend"
            ]
        ]
    ]
);
?>