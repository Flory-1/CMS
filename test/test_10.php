<?php
// Call the CMS System
$CT = new CMS(
    [
        "lg"=>"de",
        "weekend_check"=>true,
        "theme"=>[
            "theme"=>'Test',
            "custom_url"=>'test/theme.json'
        ],
        "view"=>'list_view',
        "events_check"=>true,
        "date_format"=>'d-m.Y'
    ]
);
?>