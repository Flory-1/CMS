<?php header("Content-type: text/css"); session_start();?>
html,
body {
    margin: 0;
    padding: 0;
    overflow: auto;
    background-color: <?=$_SESSION["TH"]["Main_Background"]?>;
    color: <?=$_SESSION["TH"]["Main_Color"]?>;
    font-family: <?=$_SESSION["TH"]["Font_Family"]?> !important;
    font-size: <?=$_SESSION["TH"]["Font_Size"]?> !important;
    font-weight: <?=$_SESSION["TH"]["Font_Weight"]?> !important
}

#cms_calendar[data-season="winter"] .month table {
}
#cms_calendar[data-season="summer"] .month table {
}
#cms_calendar[data-season="spring"] .month table {
}
#cms_calendar[data-season="fall"] .month table {
}

.<?=$_SESSION["TH"]["theme"]?> .cms-header {
    display: flex;
    justify-content: space-between;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-header #legend span {
    position: relative;
    display: inline-block;
    width: 6px !important;
    height: 6px !important;
    margin: 0 5px;
    padding: 5px;
    line-height: 10px
}


.<?=$_SESSION["TH"]["theme"]?> .cms-body .month table {
    background: <?=$_SESSION["TH"]["Table_Background"]?> !important;
    border-radius: <?=$_SESSION["TH"]["Border_Radius"]?> !important;
    table-layout: fixed;
    border-collapse: unset
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr:last-child td:first-child,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr:last-child td:first-child:hover {
    border-bottom-left-radius: <?=$_SESSION["TH"]["Border_Radius"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr:last-child td:last-child,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr:last-child td:last-child:hover {
    border-bottom-right-radius: <?=$_SESSION["TH"]["Border_Radius"]?>
}
.<?=$_SESSION["TH"]["theme"]?>.card {
    background: transparent
}


.<?=$_SESSION["TH"]["theme"]?> .cms-body thead tr:first-child {
    border: <?=$_SESSION["TH"]["Border_Width"]?> <?=$_SESSION["TH"]["Border_Style"]?> <?=$_SESSION["TH"]["Border_Color"]?>;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tr td {
    text-align: center;
    position: relative;
    cursor: pointer;
    border: <?=$_SESSION["TH"]["Border_Width"]?> <?=$_SESSION["TH"]["Border_Style"]?> <?=$_SESSION["TH"]["Border_Color"]?>;
    margin: 0 !important;
    height: <?=$_SESSION["TH"]["Week_Day_height"]?> !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month thead {
    background: <?=$_SESSION["TH"]["Table_head_Background"]?>;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td:not(.not-select-able) {
    background: <?=$_SESSION["TH"]["Table_foot_Background"]?>;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td.not-select-able {
    background: <?=$_SESSION["TH"]["Not_Select_Background"]?> !important;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td:hover:not(.cms-time-names):not(.cms-day-names):not(.weekend) {
    background: <?=$_SESSION["TH"]["Table_foot_Hover_Background"]?> !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td:not([data-set="none"]):not(.not-select-able) {
    background: <?=$_SESSION["TH"]["Days_Background"]?>;
    color: <?=$_SESSION["TH"]["Days_Color"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tr td:first-child {
    border-left: <?=$_SESSION["TH"]["Border_left_Width"]?> <?=$_SESSION["TH"]["Border_Style"]?> <?=$_SESSION["TH"]["Border_Color"]?>
}

.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tr td:last-child {
    border-right: <?=$_SESSION["TH"]["Border_right_Width"]?> <?=$_SESSION["TH"]["Border_Style"]?> <?=$_SESSION["TH"]["Border_Color"]?>
}

.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tr:last-child td {
    border-bottom: <?=$_SESSION["TH"]["Border_bottom_Width"]?> <?=$_SESSION["TH"]["Border_Style"]?> <?=$_SESSION["TH"]["Border_Color"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tr td.weekend {
    background: <?=$_SESSION["TH"]["Weekend_Background"]?> !important;
    color: <?=$_SESSION["TH"]["Weekend_Color"]?> !important;
    border: <?=$_SESSION["TH"]["Border_Width"]?> <?=$_SESSION["TH"]["Border_Style"]?> <?=$_SESSION["TH"]["Weekend_Border_Color"]?> !important;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tr td.weekend:hover {
    background: <?=$_SESSION["TH"]["Weekend_Hover_Background"]?> !important;
}


.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-month-name {
    font-size: <?=$_SESSION["TH"]["Month_Size"]?>;
    color: <?=$_SESSION["TH"]["Month_Color"]?>;
    background: <?=$_SESSION["TH"]["Month_Background"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-season-name {
    <!-- TODO: add styles for season name -->
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-week-days {
    font-size: <?=$_SESSION["TH"]["Month_Size"]?>;
    padding-right: 2px;
    color: <?=$_SESSION["TH"]["Month_Color"]?>;
    background: <?=$_SESSION["TH"]["Month_Background"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-week-day {
    color: <?=$_SESSION["TH"]["Table_foot_Hover_Background"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-time-names,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-day-names {
    cursor: default
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-week-time {
    height: 20px !important;
    background: #363646 !important;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-week-time.active-day {
    background: #02f2a2 !important;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-week-time-name,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-day-time-name {
    position: absolute;
    display: flex;
    top: -1px;
    z-index: 1049;
    font-size: 14px;
    font-weight: bold;
    color: #21caca;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-day-time {
    text-align: left;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month .cms-year-name {
    position: relative;
    display: inline-block;
    font-size: <?=$_SESSION["TH"]["Year_Size"]?>;
    padding-left: 6px;
    color: <?=$_SESSION["TH"]["Month_Color"]?>;
    background: <?=$_SESSION["TH"]["Month_Background"]?>
}

.<?=$_SESSION["TH"]["theme"]?> .cms-body span.day {
    position: relative;
    z-index: 10;
    left: <?=$_SESSION["TH"]["Day_left"]?> !important;
    top: <?=$_SESSION["TH"]["Day_top"]?> !important;
    font-size: <?=$_SESSION["TH"]["Day_Size"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .active-day .day {
    display: inline-block;
    width: <?=$_SESSION["TH"]["Active_Day_Width"]?> !important;
    height: <?=$_SESSION["TH"]["Active_Day_Height"]?> !important;
    border-radius: <?=$_SESSION["TH"]["Active_Day_Border_Radius"]?> !important;
    color: <?=$_SESSION["TH"]["Active_Day_Color"]?> !important;
    background: <?=$_SESSION["TH"]["Active_Day_Background"]?>;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .day_view .cms-time-split,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .week_view .cms-time-split {
    position: relative;
    display: block;
    width: 100%;
    left: 0;
    border: 1px dashed #fff;
    padding: 15px 0 0 0;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .week_view .cms-time-split.weekend {
    background: #35354f;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .week_view .cms-time-split.weekend:hover {
    background: #444 !important;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .day_view .cms-time-split:hover,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .week_view .cms-time-split:hover {
    background: #fff !important;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .day_view .cms-time-split.active-time,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .week_view .cms-time-split.active-time,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .week_view .cms-time-split.active-time.weekend {
    background: #1affbf !important;
}

.<?=$_SESSION["TH"]["theme"]?> .cms-body td span.booked,
.<?=$_SESSION["TH"]["theme"]?> .cms-body td span.booked-end,
.<?=$_SESSION["TH"]["theme"]?> .cms-body td span.booked-new,
.<?=$_SESSION["TH"]["theme"]?> .cms-body td span.booked-new-end,
.<?=$_SESSION["TH"]["theme"]?> .cms-body td span.booked-new-start,
.<?=$_SESSION["TH"]["theme"]?> .cms-body td span.booked-start {
    position: absolute;
    left: 0;
    right: 0;
    display: block
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td ul {
    list-style: none;
    margin-bottom: 10px !important;
    height: calc(100% - 20px);
    width: 100%;
    position: absolute;
    top: 0 !important;
    left: 0 !important;
    margin-top: 10px !important;
    padding: 0 !important;
    overflow: hidden
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td ul li {
    height: 10px;
    width: 100%
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td ul li:first-child {
    margin-top: 10px;
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-start {
    width: 50%;
    height: 8px;
    left: unset !important;
    right: -1px !important;
    cursor: pointer;
    -webkit-clip-path: polygon(30% 0, 100% 0%, 100% 100%, 0% 100%);
    clip-path: polygon(30% 0, 100% 0%, 100% 100%, 0% 100%);
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked {
    width: 100%;
    height: 8px
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-end {
    width: 50%;
    height: 8px;
    right: unset !important;
    cursor: pointer;
    -webkit-clip-path: polygon(0 100%, 0 0, 100% 0, 60% 100%);
    clip-path: polygon(0 100%, 0 0, 100% 0, 60% 100%);
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-new-start,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-new-end,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-new {
    width: 100%;
    height: 10px;
    border: <?=$_SESSION["TH"]["Border_section_Width"]?> <?=$_SESSION["TH"]["Border_Section_Style"]?>
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-new-start.booked-new {
    border-right: unset !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-new-end.booked-new {
    border-left: unset !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-new:not(.booked-new-start):not(.booked-new-end) {
    border-left: unset !important;
    border-right: unset !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-start.st1,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked.st1,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-end.st1,
.tooltiptitle.st1 {
    background-color: <?=$_SESSION["TH"]["status_name_1"]?> !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-start.st2,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked.st2,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-end.st2,
.tooltiptitle.st2 {
    background-color: <?=$_SESSION["TH"]["status_name_2"]?> !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-start.st3,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked.st3,
.<?=$_SESSION["TH"]["theme"]?> .cms-body span.booked-end.st3,
.tooltiptitle.st3 {
    background-color: <?=$_SESSION["TH"]["status_name_3"]?> !important
}


.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr .not-select-able,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr .not-select-able .day,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr .not-select-able:hover .day,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr td[data-set="none"] {
    opacity: <?=$_SESSION["TH"]["Opacity"]?> !important;
    cursor: default !important;
    color: <?=$_SESSION["TH"]["Main_Color"]?> !important
}
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr .not-select-able .booked-start,
.<?=$_SESSION["TH"]["theme"]?> .cms-body .month tbody tr .not-select-able .booked-end {
    cursor: default !important
}
<?=$_SESSION["TH"]["custom"]?>