<?php
// Call the CMS System
$CT = new CMS(
    [
    "lg"=>"en",
    "events_check"=>true,
    "static_infos"=>[
        "Authors", "Version", "Language"
    ],
    "event_form"=>['active'=>true, 'action'=>'my_page.php', "modal"=>true],
    "tooltip"=>true
    ]
);
?>