<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"en",
        "tooltip"=>true,
        "events_check"=>true,
        "error_log"=>true,
        "event_form"=>[
            'active'=>true,
            'action'=>'?page_2'
        ],
        "header"=>[
            "center"=>[
                "legend"
            ],
            "right"=>[
                "prev_year",
                "next_year",
                "today"
            ],
            "url"=>'?test&id=6'
        ],
        "min_year"=>''.date("Y").'',
        "max_year"=>''.(date("Y") + 2).'',
        "my_months"=>[
            "June",
            "July",
            "August"
            ]
    ]
);
?>