<?php
// Call the CMS System
$CT = new CMS(
    [
    "events_check"=>true,
    "theme"=>"Metro",
    "time_format"=>"H.i",
    "date_format"=>"d.m.Y",
    "is_book_able"=>false,
    "weekend_check"=>true
    ]
);
?>