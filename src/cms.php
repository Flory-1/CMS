<?php
/**********************************************************************
 * CMS - Calendar Management System.                                  *
 * Creates an interactive Calendar with User Settings.                *
 * Calendar can include Bookings, Reservations or waht you want       *
 *                                                                    *
 * @package     CMS - Calendar Management System                      *
 * @author      Florian Lämmlein <florian.laemmlein@gmx.de>           *
 * @copyright   (c) 2019 - 2022 Florian Lämmlein                      *
 * @license     GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html) *
 * @see         https://github.com/Flory-1/CMS The CMS GitHub project *
 * @since       Release version: 1.0.0 at 2019-10-01                  *
 * @version     Actuall version: 2.4.0 at 2020-04-19                  *
 *********************************************************************/
/**
 * FUN: Ajax Handler for any cms.js request.
 */
if(isset($_POST["action"])) {
    // Get the current ajax Event.
    switch($_POST["action"]) {
        case 'getCMSview':
            $CT = new CMS_Ajax();
            $RS = $CT->getCMSview($_POST["view"], $_POST["start_date"]);
            echo $RS;
            break;
        case 'getLinkById':
            $CT = new CMS_Ajax();
            $RS = $CT->getLinkById($_POST);
            echo $RS;
            break;
        default:
            break;
    }
}

/**
 * CLASS: CMS - PHP Calendar creation and transport class.
 * Creates an interactive Calendar with User Settings.
 * Calendar can include Bookings, Reservations or waht you want.
 */
class CMS {
    /********************************************************
     * INFO: Static variables for the Statics and System Settings
     ********************************************************/
    private const PHP_MIN       = "5.5.3";
    private const AKTIVE        = 1;
    private const OPEN          = 2;
    private const ENDED         = 3;

    /********************************************************
     * HELP: You can change this Settings if you want.
     * INFO: User variables from the CMS_Settings array ($arrgs)
     ********************************************************/
    /**
     * The Language is set on each element that the CMS is print out.
     *
     * @var string
     */
    protected $lg = "de";

    /**
     * The Data format with is Displaying.
     *
     * @var string
     */
    protected $date_format = "Y-m-d";

    /**
     * The Time format with is Displaying.
     *
     * @var string
     */
    protected $time_format = "H:i:s";

    /**
     * The Time zone is for the Date creations.
     *
     * @var string
     */
    protected $time_zone = "Europe/Berlin";

    /**
     * This is for the Min Year in the CMS.
     *
     * @var string
     */
    protected $min_year = "2019";

    /**
     * This is for the Max Year in the CMS.
     *
     * @var string
     */
    protected $max_year = "2030";

    /**
     * This is for the Current Year in the CMS.
     *
     * @var string
     */
    protected $cur_year = "";
    
    /**
     * This is for the Current view in the CMS.
     *
     * @var string
     */
    protected $view = "year_view";

    /**
     * This is the Time split for the day_view, week_view.
     *
     * @var int
     */
    protected $time_split = 60;
    
    /**
     * This is for the Min Days of an Event, Booking needed for the cms.js script.
     *
     * @var int
     */
    protected $min_days = 0;

    /**
     * Set Max Events, Bookings per Day only works if the `$show_more_events` is true.
     *
     * @var int
     */
    protected $max_events_per_day = 3;

    /**
     * This is for all Months that the user will Display.
     *
     * @var int
     */
    protected $hidden_months = 13;
    
    /**
     * The Update check for the System.
     *
     * @var bool
     */
    protected $update_check = true;
    
    /**
     * The Season Time change check print out the Season change.
     *
     * @var bool
     */
    protected $time_change = false;
    
    /**
     * The CMS views Time live update.
     *
     * @var bool
     */
    protected $live_time = true;
    
    /**
     * The Season check print out the Seasonname and Theme.
     *
     * @var bool
     */
    protected $season_check = true;

    /**
     * The RTL Language support for the whole CMS that is printed out.
     *
     * @var bool
     */
    protected $rtl_check = false;
    
    /**
     * This is for all Weekend days that are in the current Month.
     *
     * @var bool
     */
    protected $weekend_check = false;
    
    /**
     * This is for all Months to set an auto size by the screen width/Height.
     *
     * @var bool
     */
    protected $auto_size = false;

    /**
     * This is for all Months that have Backdays.
     *
     * @var bool
     */
    protected $back_days = false;

    /**
     * If the User will hidde an Event, Booking.
     * Works only if the `test_event` is `1`.
     *
     * @var bool
     */
    protected $hidde_events = false;

    /**
     * If the User will Display all the Events, Bookings.
     *
     * @var bool
     */
    protected $events_check = false;

    /**
     * This is for all logs if is true it will Display it.
     *
     * @var bool
     */
    protected $status_logs = false;

    /**
     * Check if the cms.js Script is true to call an Clickable Function on the CMS.
     *
     * @var bool
     */
    protected $is_book_able = true;

    /**
     * Check if there are more than `$max_events_per_day` Events, Bookings on the Current Day.
     *
     * @var bool
     */
    protected $show_more_events = false;
    
    /**
     * If the User will have an Tooltip on each Booking, Event.
     *
     * @var bool
     */
    protected $tooltip = false;

    /**
     * This holds all Tooltip Functions.
     * This Functions must be Declaread in the Database as an field in the `events`.
     * Also must be Declaread in the CMSSettings class!!
     *
     * @example ['firstname', 'lastname']
     * @var array
     */
    protected $tooltip_functions = array();

    /**
     * This is for all Events if the User has Disabled the Database.
     * 
     * @var array
     */
    protected $my_events = array();

    /**
     * This is for all Months that the user will Display as Custom Months.
     * The variable `$hidden_months` is not longer work!!
     *
     * @example ['Juli', 'Juni']
     * @var array
     */
    protected $my_months = array();

    /**
     * Holds all buttons and infos in the Header.
     * 
     * @example ["left" => [], "center" => [], "right" => [], "url" => '']
     * @var string[]
     */
    protected $header = ["left" => [], "center" => [], "right" => [], "url" => ''];
    
    /**
     * If the User will have an Booking, Reservation Form.
     *
     * @example ["active" => false, "action" => '', "modal" => false, "arrivel_time" => '14:00:00', "leaving_time" => '10:00:00',false, "active_event" => '', "events" => []]
     * @var string[]
     */
    protected $event_form = ["active" => false, "action" => '', "modal" => false, "arrivel_time" => '14:00:00', "leaving_time" => '10:00:00', "active_event" => '', "events" => []];

    /**
     * This Displays all Infos from the Current CMS.
     *
     * @example ["active" => false, "authors" => false, "events" => false, "version" => false, "language" => false, "theme" => false]
     * @var array
     */
    protected $static_infos = ["active" => false, "authors" => false, "events" => false, "version" => false, "language" => false, "theme" => false];
    
    /**
     * This Displays all action Buttons from the Current CMS only in the list_view.
     *
     * @example ["active" => false, "iCal" => false, "google" => false, "yahoo" => false, "webOutlook" => false]
     * @var array
     */
    protected $actions_form = ["active" => false, "iCal" => false, "google" => false, "yahoo" => false, "webOutlook" => false];

    /**
     * Theme for the wohle CMS System, can include you owne Theme by url.
     *
     * @example ["theme" => 'Original', "custom_url" => '']
     * @var string[]
     */
    protected $theme = ["theme" => 'Original', "custom_url" => ''];

    /********************************************************
     * HELP: Do not change this Settings.
     * INFO: Ground variables for the calendar 
     ********************************************************/
    /**
     * This array holds all Settings from the json.
     *
     * @var array
     * @var string[]
     */
    protected $json = [
        "authors" => [
            "name" => "Florian Lämmlein",
            "email" => "florian.laemmlein@gmx.de"
        ],
        "statuse" => [
            [
                "AKTIVE" => true
            ],[
                "OPEN" => true
            ],[
                "ENDED" => true
            ]
        ],
        "class" => [
            "button" => [
                "prev_years" => "btn-warning",
                "next_years" => "btn-warning",
                "current_year" => "btn-primary",
                "year" => "btn-primary",
                "month" => "btn-primary",
                "week" => "btn-primary",
                "day" => "btn-primary",
                "list" => "btn-primary",
                "today" => "btn-warning",
                "static" => "btn-info",
                "submit" => "btn-success",
                "info" => "btn-info",
                "iCal" => "btn-info",
                "google" => "btn-info",
                "yahoo" => "btn-info",
                "webOutlook" => "btn-info",
                "pdf" => "btn-warning"
            ],
            "margin" => [
                "m-top" => "mt-2",
                "m-right" => "mr-2",
                "m-bottom" => "mb-2",
                "m-left" => "ml-2"
            ],
            "bookings" => [
                "start" => "booked-start",
                "end" => "booked-end",
                "booked" => "booked"
            ],
            "tooltip" => [
                "title" => "tooltiptitle"
            ],
            "statuse" => [
                "AKTIVE" => "st1",
                "OPEN" => "st2",
                "ENDED" => "st3"
            ],
            "icons" => [
                "prev_month" => "fas fa-chevron-left",
                "next_month" => "fas fa-chevron-right",
                "current_day" => "fas fa-calendar-check",
                "static" => "fas fa-info-circle",
                "button" => "fas fa-chevron-right",
                "year" => "fas fa-calendar-alt",
                "month" => "fas fa-calendar-alt",
                "week" => "fas fa-calendar-week",
                "day" => "fas fa-calendar-day",
                "list" => "fas fa-list",
                "iCal" => "",
                "google" => "",
                "yahoo" => "",
                "webOutlook" => "",
                "pdf" => "fas fa-file-pdf"
            ]
        ]
    ];

    /**
     * This array holds all Settings from the Lagnuage json.
     *
     * @var array
     */
    protected $json_lg = array();

    /**
     * This array holds all Functions as Names for each Event, Booking.
     *
     * @var string[]
     */
    protected $functions = [
            "getStart_date",
            "getEnd_date",
            "getStatus",
            "getHidden",
            "getEvent_name",
            "getEvent_desc"
        ];
    
    /**
     * This array holds all Timestamps as Names for each Event, Booking on the viewport.
     *
     * @var string[]
     */
    protected $times = [
        "Morning" => [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
        ],
        "Afternoon" => [
            12, 13, 14, 15, 16,
        ],
        "Evening" => [
            17, 18, 19, 20,
        ],
        "Night" => [
            21, 22, 23,
        ]
    ];

    /**
     * This strings holds all informations about the current Event, Booking foreach Status.
     * It will Display it if the `$tooltip` variable is true.
     *
     * @var string
     */
    protected $tooltip_text_1; // Event status 1.
    protected $tooltip_text_2; // Event status 2.
    protected $tooltip_text_3; // Event status 3.

    /**
     * This strings holds all HTML classes about the current Event, Booking foreach Status.
     *
     * @var string
     */
    protected $class_1; // Event status 1.
    protected $class_2; // Event status 2.
    protected $class_3; // Event status 3.

    /**
     * This holds all Events, Bookings as count.
     *
     * @var int
     */
    protected $allBookings;

    /**
     * In this variable we set the Current Event, Booking if we loop trow the `$EVENTS` array.
     *
     * @var int
     */
    protected $current_booking = 0;

    /**
     * This array holds all Theme Styles foreach Theme by Name.
     *
     * @var string[]
     */
    protected $theme_styles = [
        "Horizon" => [
            "theme"                          => 'Horizon',
            "Font_Family"                    => 'Comfortaa',
            "Font_Weight"                    => 'normal',
            "Opacity"                        => '0.4',
            "Border_Style"                   => 'solid',
            "Border_Section_Style"           => 'dashed',
            "Border_Radius"                  => '12px',
            "Active_Day_Border_Radius"       => '50%',
            "Day_left"                       => '45%',
            "Day_top"                        => '-10px',
            "Font_Size"                      => '14px',
            "Month_Size"                     => '24px',
            "Year_Size"                      => '12px',
            "Day_Size"                       => '14px',
            "Day_Hover_Size"                 => '20%',
            "Border_Width"                   => '1px',
            "Border_section_Width"           => '2px',
            "Border_left_Width"              => '1px',
            "Border_top_Width"               => '1px',
            "Border_right_Width"             => '1px',
            "Border_bottom_Width"            => '1px',
            "Active_Day_Width"               => '24px',
            "Active_Day_Height"              => '24px',
            "Week_Day_height"                => '64px',
            "Main_Color"                     => 'black',
            "status_name_1"                  => 'green',
            "status_name_2"                  => 'orange',
            "status_name_3"                  => 'red',
            "selection"                      => '#00ffcf',
            "Days_Color"                     => '#ffffff',
            "Month_Color"                    => '#000000',
            "Weekend_Color"                  => '#ecbaba',
            "Active_Day_Color"               => '#ffffff',
            "Main_Background"                => 'white',
            "Table_Background"               => '#8c8c8c',
            "Table_head_Background"          => '#4d72b5',
            "Not_Select_Background"          => 'black',
            "Table_foot_Background"          => 'white',
            "Days_Background"                => '#4d72b5',
            "Month_Background"               => 'none',
            "Weekend_Background"             => 'none',
            "Active_Day_Background"          => '#ff6bda',
            "Main_Hover_Background"          => 'none',
            "Table_foot_Hover_Background"    => '#1abc9c',
            "Weekend_Hover_Background"       => 'black',
            "Border_Color"                   => '#444444',
            "Table_foot_Border_Color"        => '#6ba0ff',
            "Weekend_Border_Color"           => 'none',
        ],
        "Metro" => [
            "theme"                          => 'Metro',
            "Font_Family"                    => 'Comfortaa',
            "Font_Weight"                    => 'normal',
            "Opacity"                        => '0.3',
            "Border_Style"                   => '',
            "Border_Section_Style"           => 'dashed',
            "Border_Radius"                  => '8px',
            "Active_Day_Border_Radius"       => '50%',
            "Day_left"                       => '45%',
            "Day_top"                        => '-10px',
            "Font_Size"                      => '14px',
            "Month_Size"                     => '24px',
            "Year_Size"                      => '12px',
            "Day_Size"                       => '14px',
            "Day_Hover_Size"                 => '20%',
            "Border_Width"                   => 'unset',
            "Border_section_Width"           => '2px',
            "Border_left_Width"              => 'unset',
            "Border_top_Width"               => 'unset',
            "Border_right_Width"             => 'unset',
            "Border_bottom_Width"            => 'unset',
            "Active_Day_Width"               => '24px',
            "Active_Day_Height"              => '24px',
            "Week_Day_height"                => '64px',
            "Main_Color"                     => 'black',
            "status_name_1"                  => 'green',
            "status_name_2"                  => 'orange',
            "status_name_3"                  => 'red',
            "selection"                      => 'blue',
            "Days_Color"                     => '#ffffff',
            "Month_Color"                    => '#000000',
            "Weekend_Color"                  => 'red',
            "Active_Day_Color"               => '#ffffff',
            "Main_Background"                => 'white',
            "Table_Background"               => '#525f7f',
            "Table_head_Background"          => 'white',
            "Not_Select_Background"          => '#344675',
            "Table_foot_Background"          => '#ce4646',
            "Days_Background"                => '#525f7f',
            "Month_Background"               => 'none',
            "Weekend_Background"             => '#525f7f',
            "Active_Day_Background"          => '#6ba0ff',
            "Main_Hover_Background"          => 'none',
            "Table_foot_Hover_Background"    => '#6ba0ff',
            "Weekend_Hover_Background"       => 'black',
            "Border_Color"                   => '',
            "Table_foot_Border_Color"        => '#6ba0ff',
            "Weekend_Border_Color"           => 'none',
        ],
        "Original" => [
            "theme"                          => 'Original',
            "Font_Family"                    => 'Comfortaa',
            "Font_Weight"                    => 'normal',
            "Opacity"                        => '0.5',
            "Border_Style"                   => 'solid',
            "Border_Section_Style"           => 'solid',
            "Border_Radius"                  => '6px',
            "Active_Day_Border_Radius"       => '50%',
            "Day_left"                       => '45%',
            "Day_top"                        => '-10px',
            "Font_Size"                      => '14px',
            "Month_Size"                     => '24px',
            "Year_Size"                      => '12px',
            "Day_Size"                       => '14px',
            "Day_Hover_Size"                 => '20%',
            "Border_Width"                   => '1px',
            "Border_section_Width"           => '1.5px',
            "Border_left_Width"              => '1px',
            "Border_top_Width"               => '1px',
            "Border_right_Width"             => '1px',
            "Border_bottom_Width"            => '1px',
            "Active_Day_Width"               => '24px',
            "Active_Day_Height"              => '24px',
            "Week_Day_height"                => '64px',
            "Main_Color"                     => 'black',
            "status_name_1"                  => 'green',
            "status_name_2"                  => 'orange',
            "status_name_3"                  => 'red',
            "selection"                      => 'blue',
            "Days_Color"                     => '#ffffff',
            "Month_Color"                    => '#000000',
            "Weekend_Color"                  => '#ecbaba',
            "Active_Day_Color"               => '#ffffff',
            "Main_Background"                => 'white',
            "Table_Background"               => '#e3f2fd',
            "Table_head_Background"          => '#e3f2fd',
            "Not_Select_Background"          => '#1abc9c',
            "Table_foot_Background"          => 'black',
            "Days_Background"                => '#acb2c1',
            "Month_Background"               => 'none',
            "Weekend_Background"             => '#acb2c1',
            "Active_Day_Background"          => '#6ba0ff',
            "Main_Hover_Background"          => 'none',
            "Table_foot_Hover_Background"    => '#6ba0ff',
            "Weekend_Hover_Background"       => 'black',
            "Border_Color"                   => '#444444',
            "Table_foot_Border_Color"        => 'none',
            "Weekend_Border_Color"           => '#ecbaba',
        ],
        "Jquery" => [
            "theme"                          => 'Jquery',
            "Font_Family"                    => 'Arial, Helvetica, sans-serif',
            "Font_Weight"                    => 'normal',
            "Opacity"                        => '0.5',
            "Border_Style"                   => 'solid',
            "Border_Section_Style"           => 'solid',
            "Border_Radius"                  => '6px',
            "Active_Day_Border_Radius"       => '50%',
            "Day_left"                       => '45%',
            "Day_top"                        => '-10px',
            "Font_Size"                      => '14px',
            "Month_Size"                     => '24px',
            "Year_Size"                      => '12px',
            "Day_Size"                       => '14px',
            "Day_Hover_Size"                 => '20%',
            "Border_Width"                   => '1px',
            "Border_section_Width"           => '2px',
            "Border_left_Width"              => '1px',
            "Border_top_Width"               => '1px',
            "Border_right_Width"             => '1px',
            "Border_bottom_Width"            => '1px',
            "Active_Day_Width"               => '24px',
            "Active_Day_Height"              => '24px',
            "Week_Day_height"                => '24px',
            "Main_Color"                     => 'black',
            "status_name_1"                  => 'green',
            "status_name_2"                  => 'orange',
            "status_name_3"                  => 'red',
            "selection"                      => 'blue',
            "Days_Color"                     => '#000000',
            "Month_Color"                    => '#000000',
            "Weekend_Color"                  => '#000000',
            "Active_Day_Color"               => '#000000',
            "Main_Background"                => 'white',
            "Table_Background"               => 'none',
            "Table_head_Background"          => 'none',
            "Not_Select_Background"          => '#a2ecc9',
            "Table_foot_Background"          => '#ffffff',
            "Days_Background"                => '#ededed',
            "Month_Background"               => '',
            "Weekend_Background"             => 'rgb(255, 255, 229)',
            "Active_Day_Background"          => 'rgb(255, 255, 229)',
            "Main_Hover_Background"          => '',
            "Table_foot_Hover_Background"    => '#6ba0ff',
            "Weekend_Hover_Background"       => 'rgb(255, 255, 150)',
            "Border_Color"                   => 'rgb(212, 212, 212)',
            "Table_foot_Border_Color"        => '',
            "Weekend_Border_Color"           => 'rgb(212, 212, 212)',
        ],
        "custom" => [
            "Horizon" =>[
                ".Horizon .cms-body .month tr td {
                    border: none !important;
                }"
            ],
            "Metro" => [
                ".Metro:not(.week_view) .cms-body .month tr td {
                    height: 50px !important
                }
                .Metro:not(.week_view) .cms-body .month .monthName {
                    text-align: center;
                    background: #ff8d72;
                    background-color: #ff8d72;
                    background-position-x: 0;
                    background-position-y: 0;
                    background-image: none;
                    background-size: auto;
                    background-image: linear-gradient(to bottom left, #ff8d72, #ff6491, #ff8d72);
                    background-size: 210% 210%;
                    background-position: 100% 0;
                    background-color: #ff8d72;
                    transition: all .15s ease;
                    box-shadow: none;
                    color: #fff;
                    font-size: 24px;
                    align-content: center;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    position: absolute;
                    z-index: 11;
                    pointer-events: none;
                    border-radius: 8px
                }
                .Metro:not(.week_view) .cms-body .month .cms-week-name {
                    color: #ff8d72;
                    font-size: 14px;
                    font-weight: bolder;
                    background: #344675;
                    border-top: none;
                    cursor: default !important;
                    width: 50px !important;
                    height: 50px !important
                }
                .Metro:not(.week_view) .cms-body .month table:hover .cms-month-name,
                .Metro:not(.week_view) .cms-body .month table:hover .cms-year-name {
                    opacity: .2
                }
                .Metro:not(.week_view) .cms-body .month .after-day {
                    cursor: default !important
                }
                .Metro:not(.week_view) .cms-body span.booked,
                .Metro:not(.week_view) .cms-body span.booked-new {
                    width: 100%;
                    height: 25%;
                    top: 50% !important
                }
                .Metro:not(.week_view) .cms-body span.booked-start,
                .Metro:not(.week_view) .cms-body span.booked-new-start {
                    border-top-left-radius: 4px;
                    border-bottom-left-radius: 4px;
                    clip-path: unset !important
                }
                .Metro:not(.week_view) .cms-body span.booked-end,
                .Metro:not(.week_view) .cms-body span.booked-new-end {
                    border-top-right-radius: 4px;
                    border-bottom-right-radius: 4px;
                    clip-path: unset !important
                }"
            ],
            "Jquery" => [
                ".Jquery .cms-body .month .monthName {
                    text-align: center;
                    background-color: #fff;
                    z-index: 1049;
                    pointer-events: none;
                    border-radius: 16px;
                    padding: 0 50px;
                }
                .Jquery .cms-body .month table thead td {
                    padding: unset
                }"
            ]
        ]
    ];

    /**
     * This array holds all Help functions foreach array given by Name in the constructor.
     *
     * @var string[]
     */
    protected $help_array = [
        "legend" => false,
        "prev_year" => false,
        "next_year" => false,
        "prev_month" => false,
        "next_month" => false,
        "today" => false,
        "year_view" => false,
        "month_view" => false,
        "week_view" => false,
        "day_view" => false,
        "list_view" => false,
        "theme" => true,
        "Font_Family" => true,
        "Font_Weight" => true,
        "Opacity" => true,
        "Border_Style" => true,
        "Border_Section_Style" => true,
        "Border_Radius" => true,
        "Active_Day_Border_Radius" => true,
        "Day_left" => true,
        "Day_top" => true,
        "Font_Size" => true,
        "Month_Size" => true,
        "Year_Size" => true,
        "Day_Size" => true,
        "Day_Hover_Size" => true,
        "Border_Width" => true,
        "Border_section_Width" => true,
        "Border_left_Width" => true,
        "Border_top_Width" => true,
        "Border_right_Width" => true,
        "Border_bottom_Width" => true,
        "Active_Day_Width" => true,
        "Active_Day_Height" => true,
        "Week_Day_height" => true,
        "Main_Color" => true,
        "status_name_1" => true,
        "status_name_2" => true,
        "status_name_3" => true,
        "selection" => true,
        "Days_Color" => true,
        "Month_Color" => true,
        "Weekend_Color" => true,
        "Active_Day_Color" => true,
        "Main_Background" => true,
        "Table_Background" => true,
        "Table_head_Background" => true,
        "Not_Select_Background" => true,
        "Table_foot_Background" => true,
        "Days_Background" => true,
        "Month_Background" => true,
        "Weekend_Background" => true,
        "Active_Day_Background" => true,
        "Main_Hover_Background" => true,
        "Table_foot_Hover_Background" => true,
        "Weekend_Hover_Background" => true,
        "Border_Color" => true,
        "Table_foot_Border_Color" => true,
        "Weekend_Border_Color" => true,
        "time_split" => [
            5 => true,
            10 => true,
            15 => true,
            30 => true,
        ],
        "tooltip_functions" => true
    ];

    /**
     * This array holds all Errors foreach array given by Name in the constructor.
     *
     * @var string[]
     */
    protected $error_array = [];
    
    /**
     * The GitHub version url for the Update.
     * 
     * @var string
     */
    protected $GitHub_url = "https://github.com/Flory-1/CMS/raw/master/src/cms.php";

    /**
     * This is for the Messages if `status_log` is true it will Display it.
     *
     * @var string
     */
    protected $status_msg;

    /**
     * Check if there is an call from an ajax request.
     *
     * @var bool
     */
    protected $ajax = false;
    
    /**
     * Check if the view loop is true to loop from 1 - 12.
     *
     * @var bool
     */
    protected $view_break = false;
    
    /**
     * Holds the current version of the CMS.
     *
     * @var string
     */
    protected $version;

    /**
     * This array holds all Events, Bookings from the Database.
     *
     * @var array
     */
    protected $EVENTS = array();

    /**
     * Tis array holds all Month Names from the Language .json file.
     *
     * @var array
     */
    protected $month_names = array();

    /**
     * Tis array holds all Times from the times_split calculation.
     *
     * @var array
     */
    protected $times_check = array();
    
    /**
     * Tis array holds the Time change Events.
     *
     * @var array
     */
    protected $time_changes = array();

    /**
     * Here are the Previus Year from the Current.
     *
     * @var int
     */
    protected $prev_year;

    /**
     * Here are the Next Year from the Current.
     *
     * @var int
     */
    protected $next_year;

    /**
     * Check if the Year Change is true to set up the Next Year.
     *
     * @var bool
     */
    protected $year_change = false;

    /**
     * Here are the Previus Month from the Current.
     *
     * @var int
     */
    protected $prev_month;

    /**
     * Here are the Next Month from the Current.
     *
     * @var int
     */
    protected $next_month;
    
    /**
     * Here are the current Year.
     *
     * @var int
     */
    protected $current_year;
    
    /**
     * Here are the current Month.
     *
     * @var int
     */
    protected $current_month;
    
    /**
     * Set in the Event, Booking Form the Submit Button to Disabled or not.
     *
     * @var string
     */
    protected $check_year;

    /**
     * Set up the second_persons for the cms.js Script if there are second_persons for the Current Event, Booking.
     *
     * @var int
     */
    protected $second_persons;

    /**
     * Setup an Second_price for an Event, Booking.
     *
     * @var int
     */
    protected $second_price;

    /**
     * This string holds the HTML class about the current Day.
     *
     * @var string
     */
    protected $class_td;

    /********************************************************
     * INFO: initialise the CMS System with all Settings.
     ********************************************************/
    public function __construct($arrgs = array()) {
        // initialise all Variables for the Calendar script.
        if($this->initialise($arrgs)) {
            // Read the .json file and set all informations into an array.
            if(file_exists(dirname(__FILE__)."/language.json")) {
                $this->json_lg = json_decode(file_get_contents(dirname(__FILE__)."/language.json"), true);
                // Check if the lg name is existing in the .json file and if is active.
                if(!isset($this->json_lg['check_lg'][$this->lg])) {
                    $this->status_log('check_lg', __CLASS__, __FUNCTION__, __LINE__);
                }
                // Create the version variable.
                $this->version = trim(preg_split("/[:at]/", file(__FILE__)[12])[3]);
            } else {
                exit("Sorry about that, but the language.json file dose not exist. \n The language.json file is important for the CMS System!");
            }
            // Check if there is an ajax call.
            if(!$this->ajax) {
                // Create the javascript tag.
                $this->_drawJSscript();

                // Check if there is an Update MSG.
                if(isset($_SESSION["cms_msg"])) {
                    $this->notify($_SESSION["cms_msg"]);
                    unset($_SESSION["cms_msg"]);
                }
                // Check if the PHP version is lower than the version we need.
                if(CMS::PHP_MIN > PHP_VERSION) {
                    $this->status_log('php_version', __CLASS__, __FUNCTION__, __LINE__);
                }
                // Finaly check all Functions for the Calendar.
                if(!$this->checkAllFunctions()) {
                    $this->status_log('functions_check', __CLASS__, __FUNCTION__, __LINE__);
                }
                // Check if the User wants an Update the `$checkUpdate`.
                if($this->update_check) {
                    if($this->checkUpdate()) {
                        // Check if the PHP header is sending.
                        if(!headers_sent()) {
                            $_SESSION["cms_msg"] = 'update_check';
                            header("Refresh:0");
                            exit();
                        }
                        else {
                            $_SESSION["cms_msg"] = 'update_check';
                            echo("<meta http-equiv='refresh' content='1'>");
                            exit();
                        }
                    }
                }
            }
            // Check if the User has given an array with Bookings, Events.
            if(count($this->my_events) > 0) {
                $this->allBookings = count($this->my_events);
            }
            else {
                exit($this->json_lg['my_events_check'][$this->lg]);
            }
            // Check if there is an ajax call.
            if(!$this->ajax) {
                // Change the Theme only if is existing.
                $this->change_theme();
            }
            // Check with language the Month Names are have.
            for($i = 1; $i <= 12; $i++) {
                array_push($this->month_names, $this->json_lg['month_name_'.$i][$this->lg]);
            }
            // save the Settings in the Session if the CMS is called 1 times.
            if(!isset($_SESSION["CMS"]) || isset($_SESSION["CMS"]) && $_SESSION["CMS"] != "") {
                $_SESSION["CMS"] = $arrgs;
            }
            // Check the given Timezone from User.
            if(!$this->checkTimeZone()) {
                $this->status_log('time_zone_check', __CLASS__, __FUNCTION__, __LINE__);
            }
        } else {
            exit("<br>Please check all Settings there are some Problems.
                    <br>&emsp;1. Check if you have given the right Names for the Settings.
                    <br>&emsp;2. Check if you have given any empty Settings parameter.
                    <br>&emsp;3. Check if you used Settings that are supportet by this version of the CMS System(".trim(preg_split("/[:at]/", file(__FILE__)[12])[3]).")
                    <br><br>Here are all Errors from the Settings you have take:
                    <br><pre style='color: red;'>".print_r($this->error_array, true)."</pre>");
        }
        // Call cleanup method after script execution finishes or exit is called.
        register_shutdown_function(array($this, 'destroy'), true);
    }

    /**
     * FUN: Checks all user Settings given by the $arrgs array.
     * 
     * Setup the Calendar variables and start the script.
     */
    protected function initialise($arrgs = array()): bool {
        $check = array(true);
        // Loop trought each key from User Settings.
        foreach($arrgs as $key => $value) {
            // Check if the value is an array.
            if(is_array($value)) {
                // Loop trought each value as 2 array.
                foreach((array) $value as $Ckey => $row) {
                    // Check if key is existing in the Settings.
                    if(isset($this->$key) || isset($this->$key[$Ckey])) {
                        // Check if key is an array and value is not null.
                        if(is_numeric($Ckey) && !is_array($row) && !isset($this->$key[$Ckey])) {
                            array_push($this->$key, $row);
                        }
                        // Check if the key as array is existing and value is not an array.
                        else if(is_string($Ckey) && !is_array($row) && isset($this->$key[$Ckey])) {
                            if(is_string($row)) {
                                $this->$key[$Ckey] = $row;
                            }
                            else if(!is_string($row)) {
                                $this->$key[$Ckey] = $row;
                            }
                            else {
                                if(is_string($row)) {
                                    $this->error_array[$key][$Ckey] = $row." --key are not izhzrtn the CMS System.";
                                } else {
                                    $this->error_array[$key][$Ckey] = $row." --key must be type of: string.";
                                }
                                array_push($check, false);
                            }
                        }
                        // Check if the key is existing in help_array.
                        else if(isset($this->help_array[$key]) && is_string($Ckey) && !isset($this->$key[$Ckey])) {
                            $this->$key[$Ckey] = $row;
                        }
                        // the value of the 2 array is also an array.
                        else if(is_array($row)) {
                            // Loop trought each row as 3 array.
                            foreach($row as $Dkey => $val) {
                                // Check if the key is existing in the help array.
                                if(array_key_exists($Dkey, $this->help_array) || !isset($this->$key[$Ckey][$Dkey]) && is_numeric($val) || !is_numeric($Dkey) && $val != "" || isset($this->$key[$Ckey]) && is_numeric($Dkey)) {
                                    $this->$key[$Ckey][$Dkey] = $val;
                                }
                                else if(array_key_exists($val, $this->help_array)) {
                                    $this->$key[$Ckey][$val] = true;
                                }
                                else {
                                    if(!is_numeric($Dkey)) {
                                        $this->error_array[$key][$Ckey] = $Dkey." --key are not in the CMS System.";
                                    } else {
                                        $this->error_array[$key][$Ckey] = $Dkey." --key must be type of: numeric.";
                                    }
                                    array_push($check, false);
                                }
                            }
                        } else {
                            if(isset($this->$key[$Ckey])) {
                                $this->error_array[$key][$Ckey] = $row." --key must be type of: numeric, string or array.";
                            } else {
                                $this->error_array[$key][$Ckey] = $row." --key are not in the CMS System.";
                            }
                            array_push($check, false);
                        }
                    } else {
                        $this->error_array[$key][$Ckey] = $row." --key are not in the CMS System.";
                        array_push($check, false);
                    }
                }
            }
            // Check is not an array.
            else {
                // Check if key is existing in the Settings.
                if(isset($this->$key)) {
                    // Check if the key and the value is existing in the help_array.
                    if(array_key_exists($key, $this->help_array) && array_key_exists($value, $this->help_array[$key]) || !array_key_exists($key, $this->help_array) && isset($this->$key)) {
                        $this->$key = $value;
                    }
                    else {
                        $this->error_array[$key] = $value." --key are not in the CMS System.";
                        array_push($check, false);
                    }
                } else {
                    $this->error_array[$key] = $value." --key are not in the CMS System.";
                    array_push($check, false);
                }
            }
        }
        // Return true if all Settings are correct.
        return count($check) == array_sum($check) ? true : false;
    }

    /**
     * FUN: Check if all Functions that we need for the Calendar are existing in the CMS_Settings class.
     */
    protected function checkAllFunctions(): bool {
        $check = array(true);
        // Loop trow the Functions array to check each Function.
        foreach($this->functions as $key => $value) {
            if(method_exists('CMS_Settings', $value)) {
                array_push($check, true);
            } else {
                array_push($check, false);
            }
        }
        // Check the array if all functions are existing return result.
        return array_sum($check) == count($check) ? true : false;
    }

    /**
     * FUN: Change the System Theme.
     * Get all Variables from the user file or by the internal Theme array.
     */
    public function change_theme() {
        // Check if the Theme is in the cms or from User by url.
        if(isset($this->theme_styles[$this->theme["theme"]]) && $this->theme["custom_url"] == "") {
            // Check if the Theme is an array.
            if(is_array($this->theme_styles[$this->theme["theme"]])) {
                // Check if the custom array is not null for the current Theme.
                if(isset($this->theme_styles["custom"][$this->theme["theme"]])) {
                    // Loop trow the Theme variables to set it into the $this->theme array.
                    foreach($this->theme_styles[$this->theme["theme"]] as $key => $value) {
                        $_SESSION["TH"][$key] = $value;
                    }
                    // Loop trow the custom css sytles.
                    $_SESSION["TH"]["custom"] = "";
                    $count = count($this->theme_styles["custom"][$this->theme["theme"]]);
                    for($i = 0; $i < $count; $i++) {
                        $_SESSION["TH"]["custom"] .= $this->theme_styles["custom"][$this->theme["theme"]][$i];
                    }
                } else {
                    // Loop trow the Theme variables to set it into the $this->theme array.
                    foreach($this->theme_styles[$this->theme["theme"]] as $key => $value) {
                        $_SESSION["TH"][$key] = $value;
                    }
                }
            } else {
                $this->status_log('theme_var_not_found', __CLASS__, __FUNCTION__, __LINE__);
            }
        }
        // Theme is not from cms get Theme from url.
        else if($this->theme["custom_url"] != "" && file_exists($this->theme["custom_url"])) {
            $theme_styles = json_decode(file_get_contents($this->theme["custom_url"]), true);

            // Check if the Themename is not one of the CMS Themes.
            if(isset($this->theme_styles[$theme_styles["theme"]]) || $this->theme["theme"] != $theme_styles["theme"]) {
                $this->notify('theme_var_set', __CLASS__, __FUNCTION__, __LINE__);
            }
            // Loop trought the Theme variable to set it into the `$_SESSION` array.
            foreach($theme_styles as $key => $value) {
                // Check each element that is existing in the CMS System.
                if(array_key_exists($key, $this->help_array)) {
                    $_SESSION["TH"][$key] = $value;
                    $this->help_array[$key] = $value;
                } else {
                    $this->status_log(['theme_var_not_found', $key], __CLASS__, __FUNCTION__, __LINE__);
                }
            }
        }
        // No Theme is found.
        else {
            $this->status_log('theme_not_found', __CLASS__, __FUNCTION__, __LINE__);
        }
        // Display the css file.
        echo '<link rel="stylesheet" href="css/styles.css.php" type="text/css">';

        // Check if the RTL support is active.
        if($this->rtl_check) {
            echo '<html dir="rtl">';
        }
    }

    /**
     * FUN: Create the Datetime object by given Time.
     */
    protected function getDateTime($tempDate = null, $YEAR = null, $MONTH = null, $DAY = null, $type = null) {
        // Check with type it is.
        switch ($type) {
            case 'week_view_start':
                return DateTime::createFromFormat("d/m/Y", "".date('d')."/{$MONTH}/{$YEAR}", new DateTimeZone($this->time_zone));
            case 'week_view_time':
                return DateTime::createFromFormat("H/i/s", "{$tempDate}/00/00", new DateTimeZone($this->time_zone));
            case 'event_start_date':
                return DateTime::createFromFormat("d/m/Y", "1/{$MONTH}/{$YEAR}", new DateTimeZone($this->time_zone));
            case 'event_end_date':
                return DateTime::createFromFormat("d/m/Y", "{$DAY}/{$MONTH}/{$YEAR}", new DateTimeZone($this->time_zone));
            case 'time_changes_start_date':
                return DateTime::createFromFormat("Y-m-d\TH:i:sP", $this->time_changes[0]["time"], new DateTimeZone($this->time_zone));
            case 'time_changes_end_date':
                return DateTime::createFromFormat("Y-m-d\TH:i:sP", $this->time_changes[1]["time"], new DateTimeZone($this->time_zone));
            case 'start_date':
                return DateTime::createFromFormat("d/m/Y", "1/1/{$this->current_year}", new DateTimeZone($this->time_zone));
            case 'end_date':
                return DateTime::createFromFormat("d/m/Y", "{$tempDate->format("t")}/12/{$this->current_year}", new DateTimeZone($this->time_zone));
            case 'event_end':
                return DateTime::createFromFormat("d/m/Y", date("d/m/Y", strtotime($tempDate)), new DateTimeZone($this->time_zone));
            case 'check_month':
                return DateTime::createFromFormat("d/m/Y", date("d/m/{$YEAR}", strtotime($tempDate)), new DateTimeZone($this->time_zone));
            case 'time':
                return DateTime::createFromFormat("d/m/Y H:i:s", date("d/m/Y H:i:s"), new DateTimeZone($this->time_zone));
            default:
                return DateTime::createFromFormat("d/m/Y", date("d/m/Y"), new DateTimeZone($this->time_zone));
        }
    }

    /**
     * FUN: Check if the given Timezone is existing in PHP.
     */
    protected function checkTimeZone(): bool {
        $ZONES = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        // Check if there is an match in the time zone list.
        foreach($ZONES as $value) {
            if($this->time_zone == $value) {
                return true;
            }
        }
        // There are no match return false.
        $this->time_zone = "Europe/Berlin";
        return false;
    }

    /**
     * FUN: Get an Key from the json file.
     * By the given Key and Name.
     */
    protected function getKey($key, $name = array()) {
        $RS = "";
        // Check if the name in the array is existing in the .json file.
        foreach($name as $Ckey => $row) {
            if(isset($this->json[$key][$Ckey][$row])) {
                $RS .= $this->json[$key][$Ckey][$row]." ";
            }
            else if(isset($this->json[$key][$Ckey])) {
                $RS .= $this->json[$key][$Ckey]." ";
            }
            else if(isset($this->json[$key][$row])) {
                $RS .= $this->json[$key][$row]." ";
            }
        }
        return trim($RS);
    }

    /**
     * FUN: Check the Given Months (User Months) by index.
     * 
     * if month is older than current month return false.
     * else retun the index as month.
     */
    protected function ceckGivenMonths($YEAR) {
        $startDate = $this->getDateTime();
        $check = array(true);
        // Check if the array is not empty and if there are no issues between the month names.
        if(count(array_intersect($this->month_names, $this->my_months)) > 0) {
            // Check the Date foreach month name (User Months).
            // if the current month is lower than the month retun false.
            foreach($this->my_months as $key => $value) {
                $endDate = $this->getDateTime($value, $YEAR, "", "", "check_month");
                // Check if the date is lower or higger than todays date.
                if($startDate >= $endDate) {
                    array_push($check, false);
                } else {
                    array_push($check, true);
                }
            }
            // check if there is an Date older than todys date.
            if(array_sum($check) == count($check)) {
                return array_intersect($this->month_names, $this->my_months);
            }
            $this->notify('month_date');
        }
        return false;
    }

    /**
     * FUN: Create the Calender with all Settings you have set.
     */
    public function showCalendar($year, $month_index = "") {
        // Check if month_index is null.
        if($month_index == "" || $month_index == null || $month_index <= 0) {
            $month_index = 1;
        }
        // Setup the current year and month.
        if($this->cur_year != "") {
            $this->current_year = intval(trim($this->cur_year));
        } else {
            $this->current_year = intval(trim($year));
        }
        $this->current_month = intval(trim($month_index));

        // Create an new Date object for start, end date.
        $startDate = $this->getDateTime("", "", "", "", "start_date");
        $endDate = $this->getDateTime($startDate, "", "", "", "end_date");

        // Check if the time_chage is true.
        if($this->time_change) {
            $this->time_changes = $this->getSeasonTimeChange($startDate->format("Y"));
            $this->time_changes["start_date"] = $this->getDateTime("", "", "", "", "time_changes_start_date");
            $this->time_changes["end_date"] = $this->getDateTime("", "", "", "", "time_changes_end_date");
        }
        // Create all times as an array.
        for($i = 0; $i <= 60; $i += $this->time_split) {
            array_push($this->times_check, ($i < 10 ? "0".$i : $i).":00");
        }
        // Loop trow the my_events array and set each Event, Booking as new CMS_Settings class.
        foreach($this->my_events as $data) {
            $EVENT_End = $this->getDateTime($data["end_date"], "", "", "", "event_end");
            // Check the Date and with status are the Event, Reservation have.
            if(($EVENT_End >= $startDate) && ($EVENT_End <= $endDate)) {
                array_push($this->EVENTS, new CMS_Settings($data));
            }
        }
        // Create the Header with Buttons and Legend.
        echo '<div class="'.$this->theme['theme'].' '.$this->view.'" '.($this->season_check ? "data-season=\"{$this->getYearSeason($this->current_month)}\"" : "").' name="cms_calendar" id="cms_calendar">
            <div class="cms-header">';
                $this->_drawCalendarHeader();
        echo '</div>
            <div class="cms-body"><div class="col-12 row">';

        // Get the auto_size from view port.
        if($this->view == "week_view" || $this->view == "day_view" || $this->view == "list_view") {
            $this->auto_size = true;
        }
        // Loop trow all Months as index.
        if(count($this->my_months) > 0 && $this->my_months[0] != "") {
            // Check the Months that the User has given in the array.
            $month = $this->ceckGivenMonths($this->current_year);

            // Printout Error if the User has given false Month names exit.
            if(!is_array($month) && !$month) {
                $this->status_log('check_months', __CLASS__, __FUNCTION__, __LINE__);
            }
            // No Error User has given right Month Names print out Calendar.
            for($i = 1; $i <= 12; $i++) {
                // Printout Months with Bookings inside.
                if(array_key_exists($i, $month)) {
                    // Create the current Month and get the Name by index.
                    $name = $month[$i];
                    $j = array_search($name, $month) +1;
                
                    // Check if the loop is braken by the view port limit.
                    if(!$this->view_break) {
                        // Display the current Month with Events, Bookings inside.
                        echo "<div class=\"month table-responsive ".($this->auto_size ? 'col' : 'col-xs-4 col-md-6 col-lg-4')."\">";
                            $this->getFunction($this->view, [($j == 13 ? 1 : $j), $this->getYear($this->current_year, $j)] );
                        echo "</div>";
                    }
                }
            }
        } else {
            // Create the new index for $hidden_months.
            $this->hidden_months = ($this->hidden_months + $this->current_month);
            // Create each Month with new index and Bookings inside.
            for($i = $this->current_month; $i <= $this->hidden_months; $i++) {
                // if hidde same as Month index break the loop.
                if($this->hidden_months < ($i +1) || $i >= 13) {
                    break;
                }
                // Check if the loop is braken by the view port limit.
                if(!$this->view_break) {
                    // Display the current Month with Events, Bookings inside.
                    echo "<div class=\"month table-responsive ".($this->auto_size ? 'col' : 'col-xs-4 col-md-6 col-lg-4')."\">";
                        $this->getFunction($this->view, [($i == 13 ? 1 : $i), $this->getYear($this->current_year, $i)] );
                    echo "</div>";
                }
            }
        }
        // End of the cms-body print out cms-footer.
        echo '</div></div>
        <div class="cms-footer">';

        // Create the Event Form if ajax is false.
        if($this->event_form['active']) {
            // Check the given year if is lower than current.
            $this->check_year = $this->current_year < date("Y") ? "disabled" : "";
            
            // Create foreach Events in form an option if the function is active.
            if($this->event_form["events"] != null) {
                foreach ($this->event_form["events"] as $key => $value) {
                    // Check if there is an selected event.
                    $key == $this->event_form["active_event"] ? $class = "selected='selected'" : $class = "";

                    // Check if the key is existing in the form.
                    if(!isset($this->event_form["event_options"])) {
                        $this->event_form["event_options"] = "<option ".$class." value='".$value."'>".$value."</option>";
                    }
                    else {
                        $this->event_form["event_options"] .= "<option ".$class." value='".$value."'>".$value."</option>";
                    }
                }
            }
            // There are no events given from the user.
            else {
                $this->event_form["event_options"] = "";
            }
            // Print form if ajax is false.
            if($this->event_form['modal']) {
                $this->_drawBookingFormModal();
            } else {
                $this->_drawBookingForm();
            }
        }
        // Create the Static Modal if is activated if ajax is false.
        if($this->static_infos["active"]) {
            $this->_drawStaticModal();
        }
        // Create the Events, Bookings Modal.
        $this->_drawUlModal();

        // End of CMS System.
        echo '</div></div>';

        // Check if the status_msg is empty.
        if($this->status_msg == "") {
            $this->status_log('cms_create_success', __CLASS__, __FUNCTION__, __LINE__);
        } else {
            $this->status_log('cms_create_error', __CLASS__, __FUNCTION__, __LINE__);
        }
    }

    /**
     * FUN: Get an function from by Name and overgive all parameters.
     */
    protected function getFunction($fun, $arrgs) {
        // Check witch fun it is.
        switch($fun) {
            case 'Tooltip':
                // Check with index is in array and if index are the same as given.
                foreach($this->tooltip_functions as $key => $value) {
                    // Check if there is an true value, so we dont break the line.
                    if(is_string($key) && $value == true) {
                        // Check if the given function (variable is existing).
                        if($arrgs->getMethod($key) != "" || $arrgs->getProtected($key, "return") != "") {
                            // Check if the `$RS` is set.
                            if(!isset($RS)) {
                                // Check if the function (variable) is an `$_methods` or an `$variable`.
                                if($arrgs->getMethod($key) != "") {
                                    $RS = $arrgs->getMethod($key)." ";
                                }
                                else {
                                    $RS = $arrgs->getProtected($key, "return")." ";
                                }
                            }
                            else {
                                // Check if the function (variable) is an `$_methods` or an `$variable`.
                                if($arrgs->getMethod($key) != "") {
                                    $RS .= $arrgs->getMethod($key)." ";
                                }
                                else {
                                    $RS .= $arrgs->getProtected($key, "return")." ";
                                }
                            }
                        }
                    }
                    // There is no line break.
                    else {
                        // Check if the given function (variable is existing).
                        if($arrgs->getMethod($value) != "" || $arrgs->getProtected($value, "return") != "") {
                            // Check if the `$RS` is set.
                            if(!isset($RS)) {
                                // Check if the function (variable) is an `$_methods` or an `$variable`.
                                if($arrgs->getMethod($value) != "") {
                                    $RS = $arrgs->getMethod($value)."<br>";
                                }
                                else {
                                    $RS = $arrgs->getProtected($value, "return")."<br>";
                                }
                            }
                            else {
                                // Check if the function (variable) is an `$_methods` or an `$variable`.
                                if($arrgs->getMethod($value) != "") {
                                    $RS .= $arrgs->getMethod($value)."<br>";
                                }
                                else {
                                    $RS .= $arrgs->getProtected($value, "return")."<br>";
                                }
                            }
                        }
                    }
                }
                // Return the result.
                return isset($RS) ? $RS : null;
                break;
            case 'year_view':
                $this->view = 'year_view';
                // Check the arrgs array if is from ajax.
                if(!is_array($arrgs)) {
                    $this->_drawCalendarYearView(date("m", strtotime($arrgs)), date("Y", strtotime($arrgs)));
                } else {
                    $this->_drawCalendarYearView($arrgs[0], $arrgs[1]);
                }
                break;
            case 'month_view':
                $this->view = 'month_view';
                // Check the arrgs array if is from ajax.
                if(!is_array($arrgs)) {
                    $this->_drawCalendarMonthView(date("m", strtotime($arrgs)), date("Y", strtotime($arrgs)));
                } else {
                    $this->_drawCalendarMonthView($arrgs[0], $arrgs[1]);
                }
                break;
            case 'week_view':
                $this->view = 'week_view';
                // Check the arrgs array if is from ajax.
                if(!is_array($arrgs)) {
                    $this->_drawCalendarWeekView(date("m", strtotime($arrgs)), date("Y", strtotime($arrgs)));
                } else {
                    $this->_drawCalendarWeekView($arrgs[0], $arrgs[1]);
                }
                break;
            case 'day_view':
                $this->view = 'day_view';
                // Check the arrgs array if is from ajax.
                if(!is_array($arrgs)) {
                    $this->_drawCalendarDayView(date("d", strtotime($arrgs)), date("m", strtotime($arrgs)), date("Y", strtotime($arrgs)));
                } else {
                    $this->_drawCalendarDayView((isset($arrgs[2]) ? $arrgs[2] : date("d")), $arrgs[0], $arrgs[1]);
                }
                break;
            case 'list_view':
                $this->view = 'list_view';
                // Check the arrgs array if is from ajax.
                if(!is_array($arrgs)) {
                    $this->_drawCalendarListView(date("m", strtotime($arrgs)), date("Y", strtotime($arrgs)));
                } else {
                    $this->_drawCalendarListView($arrgs[0], $arrgs[1]);
                }
                break;
        }
    }

    /**
     * FUN: Create the Tooltip with status and some informations about the Resevation, Event.
     */
    protected function createTooltip($EVENT): string {
        if($EVENT->getStatus() == CMS::AKTIVE) {
            return $this->tooltip_text_1 = "<span class='".$this->getKey("class", ["tooltip" => "title", "statuse" => "AKTIVE"])."'>".$this->json_lg["status_name_1"][$this->lg].":</span>\n
            ".$EVENT->getEvent_name()."<br>"."
            ".$this->getFunction("Tooltip", $EVENT)."
            ".(
                $EVENT->getStart_date()->format($this->date_format) == $EVENT->getEnd_date()->format($this->date_format) ? 
                    $EVENT->getStart_date()->format($this->date_format) :
                    $EVENT->getStart_date()->format($this->date_format)." - ".$EVENT->getEnd_date()->format($this->date_format)
            );
        } 
        else if($EVENT->getStatus() == CMS::OPEN) {
            return $this->tooltip_text_2 = "<span class='".$this->getKey("class", ["tooltip" => "title", "statuse" => "OPEN"])."'>".$this->json_lg["status_name_2"][$this->lg].":</span>\n
            ".$EVENT->getEvent_name()."<br>"."
            ".$this->getFunction("Tooltip", $EVENT)."
            ".(
                $EVENT->getStart_date()->format($this->date_format) == $EVENT->getEnd_date()->format($this->date_format) ? 
                    $EVENT->getStart_date()->format($this->date_format) :
                    $EVENT->getStart_date()->format($this->date_format)." - ".$EVENT->getEnd_date()->format($this->date_format)
            );
        } 
        else if($EVENT->getStatus() == CMS::ENDED) {
            return $this->tooltip_text_3 = "<span class='".$this->getKey("class", ["tooltip" => "title", "statuse" => "ENDED"])."'>".$this->json_lg["status_name_3"][$this->lg].":</span>\n
            ".$EVENT->getEvent_name()."<br>"."
            ".$this->getFunction("Tooltip", $EVENT)."
            ".(
                $EVENT->getStart_date()->format($this->date_format) == $EVENT->getEnd_date()->format($this->date_format) ? 
                    $EVENT->getStart_date()->format($this->date_format) :
                    $EVENT->getStart_date()->format($this->date_format)." - ".$EVENT->getEnd_date()->format($this->date_format)
            );
        } else {
            $this->status_log('status_check', __CLASS__, __FUNCTION__, __LINE__);
        }
    }

    /**
     * FUN: Return only String remove html, css, js and php code from input.
     */
    protected function only_string($string) {
        return !preg_match("#[^a-zA-Zä-Ü0-9 -.:<>$?\/@ß&_]#i", $string) ? htmlentities(htmlspecialchars(strip_tags(preg_replace("/[^a-zA-Zä-Ü0-9 -.:@ß&_]/", "", trim($string)))), ENT_QUOTES | ENT_IGNORE, "UTF-8") : null;
    }
    
    /**
     * FUN: Check Current Status from Event, Reservation if is true or false.
     */
    protected function checkStatus($EVENT): bool {
        if($EVENT != null) {
            if(isset($this->json["statuse"][($EVENT->getStatus() - 1) ])) {
                return array_values($this->json["statuse"][($EVENT->getStatus() - 1) ])[0] ? true : false;
            }
        }
        return false;
    }

    /**
     * FUN: Check the Current `test_event` Status is `1` and if hidde_event is true than return true.
     */
    protected function checkHidden($EVENT): bool {
        if($this->hidde_events) {
            return $EVENT->getHidden() == 1 ? true : false;
        }
        return false;
    }

    /**
     * FUN: Return the calculated Year.
     * 
     * if the `max_year` var is more than the current Year.
     * Also check if the Month is lower than the `hidden` var.
     */
    protected function getYear($YEAR, $MONTH): int {
        // Check if the given Month is lower than the `hidden`.
        if(($MONTH + $this->hidden_months) >= 13 && !$this->year_change && ($MONTH + 1) > 13 && $YEAR <= $this->max_year && $YEAR >= $this->min_year) {
            $this->year_change = true;
            return $YEAR += 1;
        }
        return $YEAR;
    }

    /**
     * FUN: Return the Start and End day of an Week in an Year.
     */
    protected function getStartAndEndDay($WEEK, $YEAR, $check = false) {
        $today = $this->getDateTime();

        // Check the `$check` var if is true.
        if($check) {
            $tempDate = clone $today->setISODate($YEAR, $WEEK, 1);
            $tempEndDate = clone $today->setISODate($YEAR, $WEEK, 7);
            $RS = array();
            // loop trought the Week.
            while($tempDate <= $tempEndDate) {
                $RS[] = clone $tempDate;
                $tempDate->modify('+1 day');
            }
            // Return the result.
            return $RS;
        }
        else {
            // Get the current Start and End day.
            return(object) [
                'first_day' => clone $today->setISODate($YEAR, $WEEK, 1),
                'last_day'  => clone $today->setISODate($YEAR, $WEEK, 7),
                'days' => $this->getStartAndEndDay($WEEK, $YEAR, true)
            ];
        }
    }

    /**
     * FUN: Return the current Season of the given Timestamp (by Month).
     */
    protected function getYearSeason($MONTH): string {
        // Check witch Season it is and return it.
        if($MONTH >= "03" && $MONTH <= "05") {
            return "spring";
        }
        else if($MONTH >= "06" && $MONTH <= "08") {
            return "summer";
        }
        else if($MONTH >= "09" && $MONTH <= "11") {
            return "fall";
        }
        return "winter";
    }

    /**
     * FUN: Return the current Season Time to change it (by Year).
     */
    protected function getSeasonTimeChange($YEAR) {
        // Create the Time Change by the given Time zone and Year.
        $timeZone = new DateTimeZone($this->time_zone);
        $transitions = $timeZone->getTransitions(mktime(0, 0, 0, 1, 1, $YEAR));

        // Check if there is an Time change on this Time zone.
        if(isset($transitions[1])) {
            return array_slice($transitions, 1, 2);
        }
        // There are no Time changes on this Time zone.
        return false;
    }

    /**
     * FUN: Check if Date is in range with current Event dates.
     */
    protected function isDateInRange($START_DATE, $END_DATE, $CHECK): bool {
        // Loop trought the days.
        while($START_DATE < $END_DATE) {
            // Check if current day is between.
            if($CHECK->format("d") == $START_DATE->format("d")) {
                return true;
            }
            // Modify Date +1 to loop the while.
            $START_DATE->modify('+1 day');
        }
        // Date is not between.
        return false;
    }

    /**
     * FUN: Print out the Events, Bookings from the foreach by an view.
     */
    protected function _drawEvents($weekDays, $tempDate, $setDate = null, $count = null) {
        // Create all variables we need to start.
        $setDate = $setDate == null ? $this->getDateTime("", "", "", "", "time") : $setDate;
        $dateNow = $this->getDateTime();
        
        // check if $tempDate == Active Day.
        if($tempDate == $dateNow && $dateNow->format("H") <= $setDate->format('H')) {
            $this->class_td = "active-day";
        }
        else if($tempDate < $dateNow || $tempDate == $dateNow && $dateNow->format("H") >= $setDate->format('H')) {
            $this->class_td = "not-select-able";
        }
        else if($tempDate > $dateNow) {
            $this->class_td = "";
        }
        // Check if the `$weekDays` is weekend.
        switch($this->view) {
            case 'year_view':
                if($this->weekend_check && $weekDays >= 5) {
                    echo "<td class=\"cms-year-time weekend {$this->class_td}\" id=\"{$tempDate->format("Y-m-d")}\">"
                        . "<span class=\"day\">{$tempDate->format("d")}</span>";
                } else {
                    echo "<td class=\"cms-year-time {$this->class_td}\" id=\"{$tempDate->format("Y-m-d")}\">"
                        . "<span class=\"day\">{$tempDate->format("d")}</span>";
                }
                break;
            case 'month_view':
                if($this->weekend_check && $weekDays >= 5) {
                    echo "<td class=\"cms-month-time weekend {$this->class_td}\" id=\"{$tempDate->format("Y-m-d")}\">"
                        . "<span class=\"day\">{$tempDate->format("d")}</span>";
                } else {
                    echo "<td class=\"cms-month-time {$this->class_td}\" id=\"{$tempDate->format("Y-m-d")}\">"
                        . "<span class=\"day\">{$tempDate->format("d")}</span>";
                }
                break;
            case 'week_view':
                // Check if the timestamp is matching.
                if($dateNow->format("H") == $setDate->format('H')) {
                    // Check if the `$times_check` key is existing in this array.
                    if(isset($this->times_check[$count+1])) {
                        // Check if there is an time match for the minute.
                        if($this->times_check[$count] < $dateNow->format('i:s') && $dateNow->format('i:s') <= $this->times_check[$count+1]) {
                            $this->class_td .= " active-time";
                        }
                    }
                    // There is no existing key in this `$times_check` array.
                    else if($this->times_check[$count] == $dateNow->format('i:s')) {
                        $this->class_td .= " active-time";
                    }
                }
                // Create the Event, Booking tag.
                if($this->weekend_check && $weekDays >= 5) {
                    echo "<span class=\"cms-time-split weekend {$this->class_td}\" id=\"{$setDate->format("Y-m-d H:i:s")}\">";
                } else {
                    echo "<span class=\"cms-time-split {$this->class_td}\" id=\"{$setDate->format("Y-m-d H:i:s")}\">";
                }
                break;
            case 'day_view':
                // Check if the timestamp is matching.
                if($tempDate == $dateNow && $dateNow->format("H") == $setDate->format('H')) {
                    // Check if the `$times_check` key is existing in this array.
                    if(isset($this->times_check[$count+1])) {
                        // Check if there is an time match for the minute.
                        if($this->times_check[$count] < $dateNow->format('i:s') && $dateNow->format('i:s') <= $this->times_check[$count+1]) {
                            $this->class_td .= " active-time";
                        }
                    }
                    // There is no existing key in this `$times_check` array.
                    else if($this->times_check[$count] == $dateNow->format('i:s')) {
                        $this->class_td .= " active-time";
                    }
                }
                // Create the Event, Booking tag.
                if($this->weekend_check && $weekDays > 5) {
                    echo "<span class=\"cms-time-split weekend {$this->class_td}\">";
                } else {
                    echo "<span class=\"cms-time-split {$this->class_td}\">";
                }
                break;
            default:
                if($this->weekend_check && $weekDays >= 5) {
                    echo "<td class=\"weekend {$this->class_td}\" id=\"{$tempDate->format("Y-m-d")}\">";
                } else {
                    echo "<td class=\"{$this->class_td}\" id=\"{$tempDate->format("Y-m-d")}\">";
                }
                break;
        }
        // Check if the show_more_events is true and if max_events_per_day > 3.
        if($this->show_more_events && $this->max_events_per_day > 3 && $this->EVENTS != null) {
            echo "<ul class='click_able_ul'>";
        } else {
            echo "<ul>";
        }
        // Check if on this Day is an Event.
        if($this->EVENTS != null) {
            foreach($this->EVENTS as $key => $EVENT) {
                // Check the Status from an Event, Booking and if is an Test Event, Booking.
                if($this->checkStatus($EVENT) && !$this->checkHidden($EVENT)) {
                    // Check if there is an Event on this Month.
                    if($EVENT->getStart_date()->format('m') == $tempDate->format('m') || $EVENT->getEnd_date()->format('m') == $tempDate->format('m')) {
                        // Check if the show_more_events is false and if max_events_per_day == 3.
                        if($this->show_more_events && $this->max_events_per_day != $key || !$this->show_more_events && $this->max_events_per_day == 3 && $this->max_events_per_day != $key) {
                            // Check foreach Date if start, end or between the booking Date.
                            if($tempDate->format('d.m.Y') == $EVENT->getStart_date()->format('d.m.Y') && $tempDate->format('d.m.Y') == $EVENT->getEnd_date()->format('d.m.Y')) {
                                $this->_drawCalendardayBooked($EVENT);
                                $this->current_booking++;
                                unset($this->EVENTS[$key]);
                            }
                            // Check if Event, Booking start_date is same as tempDate.
                            else if($tempDate->format('d.m.Y') == $EVENT->getStart_date()->format('d.m.Y')) {
                                // Check if the view is week_view or day_view.
                                if($this->view == "week_view" || $this->view == "day_view") {
                                    // Check if the Datetime is not Null.
                                    if($EVENT->getStart_date()->format('H') != 00 && $EVENT->getStart_date()->format('H') == $setDate->format('H')) {
                                        $this->_drawCalendardayBookedStart($EVENT);
                                    }
                                    // Datetime is Null.
                                    else if($EVENT->getStart_date()->format('H') == 00) {
                                        $this->_drawCalendardayBookedStart($EVENT);
                                    }
                                // There is no special view.
                                } else {
                                    $this->_drawCalendardayBookedStart($EVENT);
                                }
                            }
                            // Check if Event, Booking end_date is same as tempDate.
                            else if($tempDate->format('d.m.Y') == $EVENT->getEnd_date()->format('d.m.Y')) {
                                // Check if the view is week_view or day_view.
                                if($this->view == "week_view" || $this->view == "day_view") {
                                    // Check if the Datetime is not Null.
                                    if($EVENT->getEnd_date()->format('H') != 00 && $EVENT->getEnd_date()->format('H') == $setDate->format('H')) {
                                        $this->_drawCalendardayBookedEnd($EVENT, $tempDate);
                                        $this->current_booking++;
                                        unset($this->EVENTS[$key]);
                                    }
                                    // Datetime is Null.
                                    else if($EVENT->getEnd_date()->format('H') == 00) {
                                        $this->_drawCalendardayBookedEnd($EVENT, $tempDate);
                                        $this->current_booking++;
                                        unset($this->EVENTS[$key]);
                                    }
                                // There is no special view.
                                } else {
                                    $this->_drawCalendardayBookedEnd($EVENT, $tempDate);
                                    $this->current_booking++;
                                    unset($this->EVENTS[$key]);
                                }
                            }
                            // Check if tempDate is between the start_date and end_date from an Event, Booking.
                            else if($this->isDateInRange(clone $EVENT->getStart_date(), $EVENT->getEnd_date(), $tempDate)) {
                                $this->_drawCalendardayBooked($EVENT);
                            }
                            $this->clearEventVar();
                        }
                    }
                }
            }
        }
        // Check if the Event, Bookings is isset and if the Status is not Null.
        else if(isset($this->EVENTS[$this->current_booking]) && !$this->checkStatus($this->EVENTS[$this->current_booking]) ) {
            $this->current_booking++;
        }
        // Check if the `$time_changes` is existing.
        else if($this->time_change) {
            // Check the Time zone date if is start_date.
            if($tempDate->format('d.m') == $this->time_changes["start_date"]->format('d.m')) {
                // Create the Time change Event.
                $MyEVENT = [
                    "id" => '0',
                    "event_name" => $this->json_lg["time_event_name"][$this->lg]." ".$this->json_lg["summer_season"][$this->lg],
                    "my_description" => $this->json_lg["time_event_desc"][$this->lg],
                    "start_date" => $this->time_changes["start_date"]->format("d-m-Y H:i:s"),
                    "end_date" => $this->time_changes["start_date"]->format("d-m-Y H:i:s"),
                    "test_event" => '0'
                ];
                // Print out the created Season Time Event Object from CMS_Settings class.
                $this->_drawCalendardayBooked(new CMS_Settings($MyEVENT));
                $this->clearEventVar();
            }
            // Check the Time zone date if is end_date.
            else if($tempDate->format('d.m') == $this->time_changes["end_date"]->format('d.m')) {
                // Create the Time change Event.
                $MyEVENT = [
                    "id" => '0',
                    "event_name" => $this->json_lg["time_event_name"][$this->lg]." ".$this->json_lg["winter_season"][$this->lg],
                    "my_description" => $this->json_lg["time_event_desc"][$this->lg],
                    "start_date" => $this->time_changes["end_date"]->format("d-m-Y H:i:s"),
                    "end_date" => $this->time_changes["end_date"]->format("d-m-Y H:i:s"),
                    "test_event" => '0'
                ];
                // Print out the created Season Time Event Object from CMS_Settings class.
                $this->_drawCalendardayBooked(new CMS_Settings($MyEVENT));
                $this->clearEventVar();
            }
        }
        // Print End of the Day.
        echo "</ul>";

        // Check if is_book_able is true.
        if($this->is_book_able) {
            echo "<span class=\"book_able\"></span>";
        }
        // Print end of the td by the right view.
        if($this->view == "week_view") {
            echo "</ul></span>";
        }
        else if($this->view == "day_view") {
            echo "</span>";
        }
        else {
            echo "</td>";
        }
    }

    /**
     * FUN: Clear Event variables.
     */
    protected function clearEventVar() {
        $this->class_1 = "";
        $this->class_2 = "";
        $this->class_3 = "";
        $this->tooltip_text_1 = "";
        $this->tooltip_text_2 = "";
        $this->tooltip_text_3 = "";
    }

    /**
     * FUN: Check the Event status printout start_date from Event.
     */
    protected function _drawCalendardayBookedStart($EVENT) {
        if($EVENT->getStatus() == CMS::AKTIVE) {
            $this->class_1 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'AKTIVE']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else if($EVENT->getStatus() == CMS::OPEN) {
            $this->class_2 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'OPEN']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else if($EVENT->getStatus() == CMS::ENDED) {
            $this->class_3 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'ENDED']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else {
            $this->status_log('status_check', __CLASS__, __FUNCTION__, __LINE__);
        }
        // Print out the Event, Booking.
        $this->_printEventsOut();
    }

    /**
     * FUN: Check the Event status printout end_date from Event.
     */
    protected function _drawCalendardayBookedEnd($EVENT, $tempDate) {
        if($EVENT->getStatus() == CMS::AKTIVE) {
            $this->class_1 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'AKTIVE']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else if($EVENT->getStatus() == CMS::OPEN) {
            $this->class_2 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'OPEN']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else if($EVENT->getStatus() == CMS::ENDED) {
            $this->class_3 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'ENDED']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else {
            $this->status_log('status_check', __CLASS__, __FUNCTION__, __LINE__);
        }
        // Print out the Event, Booking.
        $this->_printEventsOut();
        // Check if there is an Event on the end_date from the last Event.
        $this->_drawCalendardayBookedNew($EVENT->getStatus(), $tempDate);
    }

    /**
     * FUN: Check the Event status printout date from Event.
     */
    protected function _drawCalendardayBooked($EVENT) {
        if($EVENT->getStatus() == CMS::AKTIVE) {
            $this->class_1 = $this->getKey('class', ['bookings' => 'booked', 'statuse' => 'AKTIVE']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else if($EVENT->getStatus() == CMS::OPEN) {
            $this->class_2 = $this->getKey('class', ['bookings' => 'booked', 'statuse' => 'OPEN']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else if($EVENT->getStatus() == CMS::ENDED) {
            $this->class_3 = $this->getKey('class', ['bookings' => 'booked', 'statuse' => 'ENDED']);
            if($this->tooltip) {
                $this->createTooltip($EVENT);
            }
        } else {
            $this->status_log('status_check', __CLASS__, __FUNCTION__, __LINE__);
        }
        // Print out the Event, Booking.
        $this->_printEventsOut();
    }

    /**
     * FUN: Check if there is an Event start_date == end_date from last Event.
     * Check the Status from the current and the last booking select an class by status.
     */
    protected function _drawCalendardayBookedNew($status, $tempDate) {
        if(count($this->EVENTS) > $this->current_booking) {
            $EVENT = isset($this->EVENTS[$this->current_booking]) ? $this->EVENTS[$this->current_booking] : null;
            if($EVENT != null) {
                if($tempDate == $EVENT->getStart_date()) {
                    if($EVENT->getStatus() == CMS::AKTIVE && $status == 1) {
                        $this->class_1 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'AKTIVE']);
                        $this->class_2 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'AKTIVE']);
                        if($this->tooltip) {
                            $this->createTooltip($EVENT);
                        }
                    } else if($EVENT->getStatus() == CMS::AKTIVE && $status == 2) {
                        $this->class_1 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'AKTIVE']);
                        $this->class_2 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'OPEN']);
                        if($this->tooltip) {
                            $this->createTooltip($EVENT);
                        }
                    } else if($EVENT->getStatus() == CMS::OPEN && $status == 1) {
                        $this->class_1 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'AKTIVE']);
                        $this->class_2 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'OPEN']);
                        if($this->tooltip) {
                            $this->createTooltip($EVENT);
                        }
                    } else if($EVENT->getStatus() == CMS::OPEN && $status == 2) {
                        $this->class_1 = $this->getKey('class', ['bookings' => 'end', 'statuse' => 'OPEN']);
                        $this->class_2 = $this->getKey('class', ['bookings' => 'start', 'statuse' => 'OPEN']);
                        $this->tooltip_text_1 = $this->tooltip_text_2;
                        if($this->tooltip) {
                            $this->createTooltip($EVENT);
                        }
                    } else {
                        $this->status_log('status_check', __CLASS__, __FUNCTION__, __LINE__);
                    }
                    // Print out the Event, Booking.
                    $this->_printEventsOut();
                }
            }
        }
    }

    /**
     * FUN: Print out the Created Events, Bookings from the Classes.
     */
    protected function _printEventsOut() {
        // Create the Event, Booking item and print them out.
        if($this->class_1 != "") {
            echo "<li><span class=\"{$this->class_1}\" data-toggle=\"tooltip\" title=\"{$this->tooltip_text_1}\"></span></li>";
        }
        if($this->class_2 != "") {
            echo "<li><span class=\"{$this->class_2}\" data-toggle=\"tooltip\" title=\"{$this->tooltip_text_2}\"></span></li>";
        }
        if($this->class_3 != "") {
            echo "<li><span class=\"{$this->class_3}\" data-toggle=\"tooltip\" title=\"{$this->tooltip_text_3}\"></span></li>";
        }
    }

    /**
     * FUN: Create the Header on top of Calendar.
     * check all elements variables if there is one true print this element.
     */
    protected function _drawCalendarHeader() {
        // Setup all variables for the Navigation.
        $year = $this->current_year;
        $this->prev_year = $this->current_year - 1;
        $this->next_year = $this->current_year + 1;
        $this->prev_month = $this->current_month - $this->hidden_months;
        $this->next_month = $this->current_month + $this->hidden_months;
        
        // Check if the prev_month is Negative.
        if($this->prev_month <= 0) {
            $this->prev_month = 1 + $this->hidden_months;
        }
        // Check second time if the month is lower than 12.
        if($this->prev_month < 12 && ($this->prev_month + $this->hidden_months) <= 12) {
            $this->prev_month = $this->prev_month + $this->hidden_months;
        }
        // Check if the prev_month is higger 12.
        if($this->prev_month >= 12) {
            $this->prev_month = 13 - $this->hidden_months;
        }
        // Create the Prev & Next Button to switch the Months and Years.
        $this->_drawNaviButtons();

        // Rebuild the current year variable.
        $this->current_year = $year;
    }
    
    /**
     * FUN: Create foreach Month the headers in the Calendar.
     */
    protected function _drawMonthTableHeader($date) {
        // Check witch view the user has taken.
        switch($this->view) {
            case 'year_view':
                // Create the Year Header with all Days and Weeks.
                echo "<table class=\"table table-bordered table-striped year_view\" id=\"month_{$date->format("n")}\">
                    <thead>
                        <tr>
                            <th colspan=\"7\" class=\"cms-month-name\">".$this->month_names[($date->format('n')-1)]."<span class=\"cms-year-name\">{$date->format('Y')}</span> ".($this->season_check ? '<span class="cms-season-name">'.$this->json_lg[$this->getYearSeason($date->format("m"))."_season"][$this->lg].'</span>' : '')." </th>
                        </tr>
                        <tr>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_1"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_2"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_3"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_4"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_5"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_6"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_7"][$this->lg]."</th>
                        </tr>
                    </thead>
                <tbody>";
                break;
            case 'month_view':
                // Create the Month Header with all Days and Weeks.
                echo "<table class=\"table table-bordered table-striped month_view\" id=\"month_{$date->format("n")}\">
                    <thead>
                        <tr>
                            <th colspan=\"7\" class=\"cms-month-name\">".$this->month_names[($date->format('n')-1)]."<span class=\"cms-year-name\">{$date->format('Y')}</span> ".($this->season_check ? '<span class="cms-season-name">'.$this->json_lg[$this->getYearSeason($date->format("m"))."_season"][$this->lg].'</span>' : '')." </th>
                        </tr>
                        <tr>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_1"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_2"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_3"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_4"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_5"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_6"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_7"][$this->lg]."</th>
                        </tr>
                    </thead>
                <tbody>";
                break;
            case 'week_view':
                // Get the current week start and end date.
                $days = $this->getStartAndEndDay($date->format("W"), $date->format("Y"));

                // Create the Week Header with all Days.
                echo "<table class=\"table table-bordered table-striped week_view\" id=\"month_{$date->format("n")}\">
                    <thead>
                        <tr>
                            <th colspan=\"8\" class=\"cms-month-name\"><span class=\"cms-week-days\">{$days->first_day->format('d')} - {$days->last_day->format('d')}.</span>".$this->month_names[($date->format('n')-1)]."<span class=\"cms-year-name\">{$date->format('Y')}</span> ".($this->season_check ? '<span class="cms-season-name">'.$this->json_lg[$this->getYearSeason($date->format("m"))."_season"][$this->lg].'</span>' : '')." </th>
                        </tr>
                        <tr>
                            <th class=\"cms-week-name\">".$this->json_lg["week_time"][$this->lg]."</th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_1"][$this->lg]."<span class=\"cms-week-day\">{$days->first_day->format('d')}</span></th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_2"][$this->lg]."<span class=\"cms-week-day\">{$days->days[1]->format('d')}</span></th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_3"][$this->lg]."<span class=\"cms-week-day\">{$days->days[2]->format('d')}</span></th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_4"][$this->lg]."<span class=\"cms-week-day\">{$days->days[3]->format('d')}</span></th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_5"][$this->lg]."<span class=\"cms-week-day\">{$days->days[4]->format('d')}</span></th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_6"][$this->lg]."<span class=\"cms-week-day\">{$days->days[5]->format('d')}</span></th>
                            <th class=\"cms-week-name\">".$this->json_lg["week_day_7"][$this->lg]."<span class=\"cms-week-day\">{$days->last_day->format('d')}</span></th>
                        </tr>
                    </thead>
                <tbody>";
                break;
            case 'day_view':
                // Create the Day Header with the current day.
                echo "<table class=\"table table-bordered table-striped day_view\" id=\"month_{$date->format("n")}\">
                    <thead>
                        <tr>
                            <th class=\"cms-month-name\"><span class=\"cms-day-days\">{$date->format("d")}.</span>".$this->month_names[($date->format("n")-1)]."<span class=\"cms-year-name\">{$date->format("Y")}</span> ".($this->season_check ? '<span class="cms-season-name">'.$this->json_lg[$this->getYearSeason($date->format("m"))."_season"][$this->lg].'</span>' : '')." </th>
                        </tr>
                        <tr>
                            <th class=\"cms-day-name\">".$this->json_lg["week_day_long_".(date('N', strtotime($date->format("d/m/Y")))).""][$this->lg]."</th>
                        </tr>
                    </thead>
                <tbody id=\"{$date->format("Y-m-d")}\">";
                break;
            case 'list_view':
                // Create the List Header with all columes.
                echo "<table class=\"table table-bordered table-striped list_view\" id=\"month_{$date->format("n")}\">
                    <thead>
                        <tr>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_1"][$this->lg]."</th>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_2"][$this->lg]."</th>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_3"][$this->lg]."</th>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_4"][$this->lg]."</th>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_5"][$this->lg]."</th>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_6"][$this->lg]."</th>
                            <th class=\"cms-list-name\">".$this->json_lg["list_item_7"][$this->lg]."</th>";
                            // Check if the actions is not NUll.
                            if($this->actions_form["active"]) {
                                echo "<th class=\"cms-list-name\">".$this->json_lg["list_item_8"][$this->lg].":</th>";
                            }
                            echo "
                        </tr>
                    </thead>
                <tbody>";
                break;
        }
    }

    /**
     * FUN: Create all the active Buttons.
     */
    protected function _drawNaviButtons() {
        // Create each Button with the current from if is active.
        foreach($this->header as $key => $value) {
            if($key == "left" || $key == "center" || $key == "right") {
                // Check with side the Button is.
                if($key == "left") {
                    echo '<div class="cms-left">';
                } else if($key == "center") {
                    echo '<div class="cms-center">';
                } else {
                    echo '<div class="cms-right">';
                }
                // Loop trought all buttons and draw it out.
                foreach($value as $Ckey => $row) {
                    if($Ckey == "year_view" && $row) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'year']).'" value="'.date('d.m.Y').'" onclick="create_view(\'year_view\', this);" name="year_view" id="year_view" data-toggle="tooltip" title="'.$this->json_lg["header_1"][$this->lg].'"><i class="'.$this->getKey('class', ['icons' => 'year']).'"></i></button>';
                        unset($this->header[$key][$Ckey]);
                    }
                    if($Ckey == "month_view" && $row) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'month']).'" value="'.date('d.m.Y').'" onclick="create_view(\'month_view\', this);" name="month_view" id="month_view" data-toggle="tooltip" title="'.$this->json_lg["header_2"][$this->lg].'"><i class="'.$this->getKey('class', ['icons' => 'month']).'"></i></button>';
                        unset($this->header[$key][$Ckey]);
                    }
                    if($Ckey == "week_view" && $row) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'week']).'" value="'.date('d.m.Y').'" onclick="create_view(\'week_view\', this);" name="week_view" id="week_view" data-toggle="tooltip" title="'.$this->json_lg["header_3"][$this->lg].'"><i class="'.$this->getKey('class', ['icons' => 'week']).'"></i></button>';
                        unset($this->header[$key][$Ckey]);
                    }
                    if($Ckey == "day_view" && $row) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'day']).'" value="'.date('d.m.Y').'" onclick="create_view(\'day_view\', this);" name="day_view" id="day_view" data-toggle="tooltip" title="'.$this->json_lg["header_4"][$this->lg].'"><i class="'.$this->getKey('class', ['icons' => 'day']).'"></i></button>';
                        unset($this->header[$key][$Ckey]);
                    }
                    if($Ckey == "list_view" && $row) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'list']).'" value="'.date('d.m.Y').'" onclick="create_view(\'list_view\', this);" name="list_view" id="list_view" data-toggle="tooltip" title="'.$this->json_lg["header_7"][$this->lg].'"><i class="'.$this->getKey('class', ['icons' => 'list']).'"></i></button>';
                        unset($this->header[$key][$Ckey]);
                    }
                    if($Ckey == "today" && $row) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'today']).'" onclick="window.location.href=\''.$this->header['url'].'&year='.date('Y').'&month='.date('m').'\'" data-toggle="tooltip" title="'.$this->json_lg["header_5"][$this->lg].'"><i class="'.$this->getKey('class', ['icons' => 'current_day']).'"></i></button>';
                        unset($this->header[$key][$Ckey]);
                    }
                    if($Ckey == "legend" && $row) {
                        echo "<p class=\"float-{$key}\" id=\"legend\">".$this->json_lg["legend"][$this->lg]."
                            <span style=\"background: ".(isset($this->theme_styles[$this->theme["theme"]]["status_name_1"]) ? $this->theme_styles[$this->theme["theme"]]["status_name_1"] : $this->help_array["status_name_1"]).";\"></span>".$this->json_lg["status_name_1"][$this->lg]."
                            <span style=\"background: ".(isset($this->theme_styles[$this->theme["theme"]]["status_name_2"]) ? $this->theme_styles[$this->theme["theme"]]["status_name_2"] : $this->help_array["status_name_2"]).";\"></span>".$this->json_lg["status_name_2"][$this->lg]."
                            <span style=\"background: ".(isset($this->theme_styles[$this->theme["theme"]]["status_name_3"]) ? $this->theme_styles[$this->theme["theme"]]["status_name_3"] : $this->help_array["status_name_3"]).";\"></span>".$this->json_lg["status_name_3"][$this->lg]."
                            <span style=\"background: ".(isset($this->theme_styles[$this->theme["theme"]]["selection"]) ? $this->theme_styles[$this->theme["theme"]]["selection"] : $this->help_array["selection"]).";\"></span>".$this->json_lg["selection"][$this->lg]."
                            <span style=\"background: ".(isset($this->theme_styles[$this->theme["theme"]]["Active_Day_Background"]) ? $this->theme_styles[$this->theme["theme"]]["Active_Day_Background"] : $this->help_array["Active_Day_Background"]).";\"></span>".$this->json_lg["current_day"][$this->lg]."
                        </p>";
                        unset($this->header[$key][$Ckey]);
                    }
                }
                // Create the Pagination Buttons.
                $this->_drawNaviSwitchButtons($key);
                
                // Create the Statics if is active.
                if($key == "right") {
                    if($this->static_infos["active"]) {
                        echo '<button class="btn '.$this->getKey('class', ['button' => 'static']).' '.$this->getKey('class', ['margin' => 'm-left']).'" data-toggle="modal" data-target="#staticModal"><i data-toggle="tooltip" title="'.$this->json_lg["header_6"][$this->lg].'" class="'.$this->getKey('class', ['icons' => 'static']).'"></i></button>';
                    }
                }
                // End of the toolbar.
                echo '</div>';
            }
        }
    }

    /**
     * FUN: Create all active Switch Buttons inside the Navigation.
     */
    protected function _drawNaviSwitchButtons($from = "center") {
        // Check if the prev month function is active.
        if($this->hidden_months < 13 && isset($this->header[$from]["prev_month"])) {
            if($this->current_month == 1) {
                $this->current_year = $this->current_year - 1;
                $change = true;
            }
            if($this->prev_year <= $this->min_year) {
                echo "<a href=\"{$this->header['url']}&year={$this->current_year}&month={$this->prev_month}\" "
                . "class=\"btn ".$this->getKey('class', ['button' => 'prev_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-right: 5px;\"><i class=\"{$this->getKey('class', ['icons' => 'prev_month'])}\"></i></a>";
            } else {
                echo "<a href=\"{$this->header['url']}&year={$this->current_year}&month={$this->prev_month}\" "
                . "disabled class=\"btn disabled ".$this->getKey('class', ['button' => 'prev_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-right: 5px;\"><i class=\"{$this->getKey('class', ['icons' => 'prev_month'])}\"></i></a>";
            }
            if(isset($change) && $change) {
                $this->current_year = $this->current_year + 1;
                $change = false;
            }
        }
        // Check if the prev and next year function is active.
        if(isset($this->header[$from]["prev_year"]) && isset($this->header[$from]["next_year"])) {
            if($this->prev_year >= $this->min_year) {
                echo "<a href=\"{$this->header['url']}&year={$this->prev_year}&month={$this->current_month}\" "
                . "class=\"btn ".$this->getKey('class', ['button' => 'prev_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-right: 5px;\">{$this->prev_year}</a>";
            }
                echo "<a href=\"\" class=\"btn ".$this->getKey('class', ['button' => 'current_year'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"pointer-events: none;\">{$this->current_year}</a>";
            if($this->next_year <= $this->max_year) {
                echo "<a href=\"{$this->header['url']}&year={$this->next_year}&month={$this->current_month}\" "
                . "class=\"btn ".$this->getKey('class', ['button' => 'next_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-left: 5px;\">{$this->next_year}</a>";
            }
        }
        // Check if the prev year function is active.
        else if(isset($this->header[$from]["prev_year"])) {
            if($this->prev_year >= $this->min_year) {
                echo "<a href=\"{$this->header['url']}&year={$this->prev_year}&month={$this->current_month}\" "
                . "class=\"btn ".$this->getKey('class', ['button' => 'prev_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-right: 5px;\">{$this->prev_year}</a>";
                echo "<a href=\"\" class=\"btn ".$this->getKey('class', ['button' => 'current_year'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"pointer-events: none;\">{$this->current_year}</a>";
            }
        }
        // Check if the next year function is active.
        else if(isset($this->header[$from]["next_year"])) {
            if($this->next_year <= $this->max_year) {
                echo "<a href=\"\" class=\"btn ".$this->getKey('class', ['button' => 'current_year'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"pointer-events: none;\">{$this->current_year}</a>";
                echo "<a href=\"{$this->header['url']}&year={$this->next_year}&month={$this->current_month}\" "
                . "class=\"btn ".$this->getKey('class', ['button' => 'next_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-left: 5px;\">{$this->next_year}</a>";
            }
        }
        // Check if the next month function is active.
        if($this->hidden_months < 13 && isset($this->header[$from]["next_month"])) {
            // Check if next_month is lower than 12.
            if($this->next_month <= 12) {
                if($this->current_month == 12) {
                    $this->current_year = $this->current_year +1;
                }
            }
            // Check if next_month is higger than 12.
            if($this->next_month > 12) {
                $this->next_month = 1;
                $this->current_year = $this->current_year +1;
            }
            if($this->next_month <= $this->max_year) {
                echo "<a href=\"{$this->header['url']}&year={$this->current_year}&month={$this->next_month}\" "
                . "class=\"btn ".$this->getKey('class', ['button' => 'next_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-left: 5px;\"><i class=\"{$this->getKey('class', ['icons' => 'next_month'])}\"></i></a>";
            } else {
                echo "<a href=\"#\" "
                . "disabled class=\"btn disabled ".$this->getKey('class', ['button' => 'next_years'])." ".$this->getKey('class', ['margin' => 'm-bottom'])."\" style=\"margin-left: 5px;\"><i class=\"{$this->getKey('class', ['icons' => 'next_month'])}\"></i></a>";
            }
        }
    }

    /**
     * FUN: Create Event form and show all Payments and Persons as select.
     */
    protected function _drawBookingForm() {
        echo '<div class="card">
                <div class="card-header">
                    <h4 class="card-title">'.$this->json_lg["selection_2"][$this->lg].'</h4>
                </div>
                <div class="card-body">
                    <form role="form" id="booking_calendar_form" action="'.$this->event_form['action'].'" method="post" data-toggle="validator">
                        <div class="col-12 row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="arrival">'.$this->json_lg["input_1"][$this->lg].' '.date($this->time_format, strtotime($this->event_form["arrivel_time"])).' Uhr</label>
                                    <input type="text" readonly id="arrival" name="arrival" class="form-control">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="leaving">'.$this->json_lg["input_2"][$this->lg].' '.date($this->time_format, strtotime($this->event_form["leaving_time"])).' Uhr</label>
                                    <input type="text" readonly id="leaving" name="leaving" class="form-control">
                                </div>
                            </div>';
                            // Check if the function is active and if there is more than 1 Event, Booking that the User has given.
                            if($this->event_form["events"] != null) {
                                echo '<div class="col-2">
                                    <div class="form-group">
                                        <label for="events">'.$this->json_lg["input_3"][$this->lg].'</label>
                                        <select id="events" class="custom-select">
                                            '.$this->event_form["event_options"].'
                                        </select>
                                    </div>
                                </div>';
                            }
                            echo '
                            <div class="col-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" id="book_now" name="book_now" class="btn '.$this->getKey('class', ['button' => 'submit']).' animation-on-hover btn-block" '.$this->check_year.'>'.$this->json_lg["next_step"][$this->lg].' <i class="'.$this->getKey('class', ['icons' => 'button']).'"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>';
    }

    /**
     * FUN: Create Event form as Modal and show all Payments and Persons as select.
     */
    protected function _drawBookingFormModal() {
        echo '<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="calendarModalLabel">'.$this->json_lg["selection_2"][$this->lg].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form role="form" id="booking_calendar_form" action="'.$this->event_form['action'].'" method="post" data-toggle="validator">
                                <div class="col-12 row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="arrival">'.$this->json_lg["input_1"][$this->lg].' '.date($this->time_format, strtotime($this->event_form["arrivel_time"])).' Uhr</label>
                                            <input type="text" readonly id="arrival" name="arrival" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="leaving">'.$this->json_lg["input_2"][$this->lg].' '.date($this->time_format, strtotime($this->event_form["leaving_time"])).' Uhr</label>
                                            <input type="text" readonly id="leaving" name="leaving" class="form-control">
                                        </div>
                                    </div>';
                                    // Check if the function is active and if there is more than 1 Event, Booking that the User has given.
                                    if($this->event_form["events"] != null) {
                                        echo '<div class="col-2">
                                            <div class="form-group">
                                                <label for="events">'.$this->json_lg["input_3"][$this->lg].'</label>
                                                <select id="events" class="custom-select">
                                                    '.$this->event_form["event_options"].'
                                                </select>
                                            </div>
                                        </div>';
                                    }
                                    echo '
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" id="book_now" name="book_now" class="btn '.$this->getKey('class', ['button' => 'submit']).' animation-on-hover btn-block" '.$this->check_year.'>'.$this->json_lg["next_step"][$this->lg].' <i class="'.$this->getKey('class', ['icons' => 'button']).'"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
    }

        
    /**
     * FUN: Create the Yaer view from ajax request.
     */
    protected function _drawCalendarYearView($MONTH, $YEAR) {
        // Create all variables that we need to start the while loop.
        $startDate = $this->getDateTime("", $YEAR, $MONTH, "", "event_start_date");
        $endDate = $this->getDateTime("", $YEAR, $MONTH, $startDate->format("t"), "event_end_date");
        $fillStart = $startDate->format("w") - 1;
        $printedFillerDays = true;
        $days = 0;
        $tempDate = clone $startDate;
        
        // Print all Months with day names inside.
        $this->_drawMonthTableHeader($startDate);
        
        // Check how many days in this week are filled.
        if($fillStart == -1) {
            $fillStart = 6;
        }
        // Create Backdays in the current month.
        if($this->back_days) {
            $tempDate = $tempDate->modify('-' .$fillStart.' day');
        }
        // loop trow all days in this Month check on each Day if there is an Event.
        // Check also if there is more than 1 Event on this Day.
        while($tempDate <= $endDate) {
            // Fill $days with 0-7 days.
            if($days == 0) {
                echo "<tr>";
            }
            // Create empty Days if there is no date in this Day.
            if(!$this->back_days) {
                if($printedFillerDays) {
                    // loop trow the week and set the empty Days insid ethe the current week.
                    for($i = 0; $i < $fillStart; $i++) {
                        echo "<td data-set='none'></td>";
                        $days++;
                    }
                    // Disable this function because we don´t need it anymore.
                    $printedFillerDays = false;
                }
            }
            // Print all Events, Bookings out.
            $this->_drawEvents($days, $tempDate);
            
            // Modify Date +1 to loop the while.
            $tempDate->modify('+1 day');
            $days++;
            
            // Print end of week.
            if($days == 7) {
                echo "</tr>";
                $days = 0;
            }
        }
        // check if there are empty Days inside this week.
        if($days < 7 && $days != 0) {
            while($days < 7) {
                // Check if there is an Weekend Day.
                if($this->weekend_check && $this->back_days && $days >= 5) {
                    echo "<td class='weekend' data-set='none'></td>";
                } else {
                    echo "<td data-set='none'></td>";
                }
                $days++;
            }
        }
        // Print end of table.
        echo "</tbody></table>";
    }

    /**
     * FUN: Create the Month view from ajax request.
     */
    protected function _drawCalendarMonthView($MONTH, $YEAR) {
        // Draw an month and break the loop.
        $this->_drawCalendarYearView($MONTH, $YEAR);
        $this->view_break = true;
    }

    /**
     * FUN: Create the Week view from ajax request.
     */
    protected function _drawCalendarWeekView($MONTH, $YEAR) {
        // Create all variables that we need to start the loop.
        $startDate = $this->getDateTime("", $YEAR, $MONTH, "", "week_view_start");
        $days = $this->getStartAndEndDay($startDate->format("W"), $startDate->format("Y"), true);
        $count = 60 / $this->time_split;
        
        // Print all Months with day names inside.
        $this->_drawMonthTableHeader($startDate);
            // loop trought the whole Time array and create each Day with Events, Bookings inside.
            foreach($this->times as $key => $row) { // loop 4 times == Morning-Night.
                foreach($row as $Ckey => $val) { // loop 24 times == 00-24 hour.
                    $setDate = $this->getDateTime($val, "", "", "", "week_view_time");
                    // Start the new time tr == $val (00, 01, 02...).
                    echo "<tr>
                        <td class=\"cms-week-time cms-time-names\">".($Ckey == 0 ? '<span class="cms-week-time-name">'.($this->json_lg["time_name_".$key][$this->lg]).'</span>' : '')."".$setDate->format($this->time_format)."</td>";
                        // Loop trought the Events array.
                        foreach($days as $Dkey => $tempDate) { // loop 7 times == 1-7 days.
                            echo "<td class=\"cms-week-time\">";
                            // loop trought the times with the Times split function.
                            for($i = 0; $i < $count; $i++) { // loop `$count` times. ("5 == 12", "10 == 6", "15 == 4", "30 == 2", "60 == 1")
                                echo "<div>";
                                    // Print all Events, Bookings out.
                                    $this->_drawEvents($Dkey, $tempDate, $setDate, $i);
                                echo "</div>";
                            }
                            echo "</td>";
                        }
                    echo "</tr>";
                }
            }
            // End of Week view.
            echo "</tbody></table>";
        // break the loop.
        $this->view_break = true;
    }
    
    /**
     * FUN: Create the Day view from ajax request.
     */
    protected function _drawCalendarDayView($DAY, $MONTH, $YEAR) {
        // Create all variables that we need to start the loop.
        $startDate = $this->getDateTime("", $YEAR, $MONTH, $DAY, "event_end_date");
        $count = 60 / $this->time_split;

        // Print all Months with day names inside.
        $this->_drawMonthTableHeader($startDate);
            // Create the Time Table with all times from the $times array.
            foreach($this->times as $key => $value) {
                // Loop trought the Times arrays.
                foreach($value as $Ckey => $row) {
                    $setDate = $this->getDateTime($row, "", "", "", "week_view_time");
                    // Create the view.
                    echo "<tr>
                        <td class=\"cms-day-time cms-day-names\">".($Ckey == 0 ? '<span class="cms-day-time-name">'.($this->json_lg["time_name_".$key][$this->lg]).'</span>' : '')."".$setDate->format("H")."";
                        // loop trought the times with the Times split function.
                        for($i = 0; $i < $count; $i++) {
                            echo "<div>";
                                // Print all Events, Bookings out.
                                $this->_drawEvents($startDate->format("w"), $startDate, $setDate, $i);
                            echo "</div>";
                        }
                    echo "</td></tr>";
                }
            }
            // End of Day view.
            echo "</tbody></table>";
        // break the loop.
        $this->view_break = true;
    }
    
    /**
     * FUN: Create the List view from ajax request.
     */
    protected function _drawCalendarListView($MONTH, $YEAR) {
        // Create all variables that we need to start the loop.
        $startDate = $this->getDateTime("", $YEAR, $MONTH, "", "event_start_date");
        
        // Print the List Header with item names inside.
        $this->_drawMonthTableHeader($startDate);
            // Check if Event is not Null.
            if($this->EVENTS != null) {
                foreach($this->EVENTS as $key => $row) {
                    echo "<tr>
                        <td class=\"cms-list-item\">{$row->getID()}</td>
                        <td class=\"cms-list-item\">{$row->getEvent_name()}</td>
                        <td class=\"cms-list-item\">{$row->getEvent_desc()}</td>
                        <td class=\"cms-list-item\">{$row->getStart_date()->format($this->date_format)}</td>
                        <td class=\"cms-list-item\">{$row->getEnd_date()->format($this->date_format)}</td>
                        <td class=\"cms-list-item\">".($row->getStatus() == 1 ? ($row->getStatus() == 2 ? $this->json_lg["status_name_2"][$this->lg] : $this->json_lg["status_name_1"][$this->lg]) : $this->json_lg["status_name_3"][$this->lg])."</td>
                        <td class=\"cms-list-item\">".($row->getHidden() == 1 ? $this->json_lg["hidden_name_1"][$this->lg] : $this->json_lg["hidden_name_2"][$this->lg])."</td>";
                        // Check if the actions is not NUll.
                        if($this->actions_form["active"]) {
                            echo "<td class=\"cms-list-item\">";
                                // Loop trought the actions form and create all Buttons that the user want.
                                foreach($this->actions_form as $key => $value) {
                                    if($key != "active" && $value) {
                                        echo "<button class=\"btn ".$this->getKey('class', ['button' => $key]).' '.$this->getKey('class', ['margin' => 'm-left'])."\" data-id=\"{$row->getID()}\" onclick=\"create_link('".$key."', this);\" data-toggle=\"tooltip\" title=\"".$this->json_lg['button_'.$key][$this->lg]."\" ><i class=\"".$this->getKey('class', ['icons' => $key])."\"></i></button>";
                                    }
                                }
                            echo "</td>";
                        }
                        echo "</tr>";
                }
            } else {
                // Check if the actions is not NUll and Print an empty row.
                if($this->actions_form["active"]) {
                    echo "<tr>
                        <td colspan='8'>".$this->json_lg['empty_list_item'][$this->lg]."</td>
                    </tr>";
                } else {
                    echo "<tr>
                        <td colspan='7'>".$this->json_lg['empty_list_item'][$this->lg]."</td>
                    </tr>";
                }
            }
            // End of Day view.
            echo "</tbody></table>";
        // break the loop.
        $this->view_break = true;
    }

    /**
     * FUN: Create the complette JavaScript file as an script tag in the HTML file with the given language.
     */
    protected function _drawJSscript() {
        echo '<script>
        /**
        * FUN: Setup the Bootstrap Tooltip function.
        */
        $(function() {
            $("[data-toggle=\"tooltip\"]").tooltip({
                html: true,
                trigger: "hover",
            }).on("click mousedown mouseup", function () {
                $("[data-toggle=\"tooltip\"]").tooltip("hide");
            });
        });
            
        /**
        * FUN: Setup the Bootstrap Notify function.
        */
        var type = ["primary", "info", "success", "warning", "danger"];
        cms = {
            showNotification: function(color, from, align, message, icon, url = "") {
                $.notify({
                    icon: icon,
                    message: message,
                    url: url,
                    target: "_blank"
                }, {
                    newest_on_top: true,
                    mouse_over: "pause",
                    type: type[color],
                    timer: 8000,
                    placement: {
                        from: from,
                        align: align
                    }
                });
            }
        };
            
        /**
        * FUN: CMS Calender Picker script.
        */
        var color = "'.(isset($this->theme_styles[$this->theme["theme"]]["selection"]) ? $this->theme_styles[$this->theme["theme"]]["selection"] : $this->help_array["selection"]).'",
            firstdate = "",
            timer = getLiveTime();
        $(document).ready(function() {
            var firstClick = true,
                startDate,
                endDate,
                arrivalDay = "",
                leavingDay = "",
                arrivalMonth = "",
                leavingMonth = "",
                datediff = 0,
                options = {year: "numeric", month: "2-digit", day: "2-digit"},
                datemin = '.$this->min_days.';
        
                /**
                * FUN: check the arttribute of an element.
                */
                $.fn.hasAttr = function(name) {
                    return this.attr(name) !== undefined;
                };
                
                /**
                * FUN: Get the Start Date after first click and End Date after second click.
                * 
                * Show mark all cells between thes Dates and set the Date into the Event, Booking form.
                */
                $("#cms_calendar").on("click", ".month tbody td", function() {
                    var new_event = $(this).children("span:last-child"), // span new booking.
                        ul = $(this).find("ul"),                         // ul inside td.
                        li = $(this).find("ul li"),                      // li inside ul.
                        date = getDate($(this)),                         // date from the td (id).
                        check_1 = $(this).hasClass("not-select-able"),   // check if is select able.
                        check_2 = $(this).hasAttr("data-set");           // second check if is select able.

                    // Set the current year, week and Day into the Buttons.
                    if($("#year_view").length > 0) {
                        $("#year_view").attr("value", date);
                    }
                    if($("#month_view").length > 0) {
                        $("#month_view").attr("value", date);
                    }
                    if($("#week_view").length > 0) {
                        $("#week_view").attr("value", date);
                    }
                    if($("#day_view").length > 0) {
                        $("#day_view").attr("value", date);
                    }
                    if($("#list_view").length > 0) {
                        $("#list_view").attr("value", date);
                    }

                    // Check the date and mark the cells from the Table.
                    if(!check_2) {
                        if(!check_1 && new_event.hasClass("book_able")) {
                            if(firstClick || firstClick == false && firstdate > date) {
                                clear_li()
                                startDate = date;
                                if(!li.find("span").hasClass("booked-start") || !li.find("span").hasClass("booked") || !li.find("span").hasClass("booked-end")) {
                                    ul.append("<li><span style=\"border-color: "+color+";\" class=\"booked-new-start\"></span></li>");
                                    arrivalDay = startDate.getDate();
                                    arrivalMonth = startDate.getMonth() + 1;
                                    $("#arrival").attr("value", startDate.toLocaleDateString(undefined, options));
                                    firstClick = false;
                                    firstdate = date;
                                    // if the datemin is disabled.
                                    if(datemin == 0 || datemin == null) {
                                        $("#leaving").attr("value", startDate.toLocaleDateString(undefined, options));
                                        leavingDay = arrivalDay;
                                        leavingMonth = arrivalMonth;
                                        firstClick = true;
                                        if($("#calendarModal").length > 0) {
                                            $("#calendarModal").modal("show");
                                        }
                                    }
                                    return true;
                                } else {
                                    cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_1"][$this->lg].'", "fas fa-bell");
                                    firstClick = true;
                                    return false;
                                }
                            } else {
                                if(startDate < date) {
                                    endDate = date;
                                    leavingDay = endDate.getDate();
                                    leavingMonth = endDate.getMonth() + 1;
                                    if(checkDate(startDate.toLocaleDateString(), endDate.toLocaleDateString())) {
                                        if(cellsColorMarked(startDate, endDate)) {
                                            ul.append("<li><span style=\"border-color: "+color+";\" class=\"booked-new booked-new-end\"></span></li>");
                                            $("#leaving").attr("value", endDate.toLocaleDateString(undefined, options));
                                        }
                                    } else {
                                        cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_2"][$this->lg].' "+datemin+".", "fas fa-bell");
                                        firstClick = false;
                                        return false;
                                    }
                                    firstClick = true;
                                    if($("#calendarModal").length > 0) {
                                        $("#calendarModal").modal("show");
                                    }
                                } else {
                                    firstClick = true;
                                    return true;
                                }
                            }
                        } else {
                            if(new_event.hasClass("book_able")) {
                                cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_3"][$this->lg].'", "fas fa-bell");
                            } else {
                                cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_7"][$this->lg].'", "fas fa-bell");
                            }
                            return false;
                        }
                    } else {
                        return false;
                    }
                });
            
                /**
                * FUN: check the Date and return true or false if date is not valide.
                */
                function checkDate(start = $("#arrival").val(), end = $("#leaving").val()) {
                    // Check if the datmin is `0`.
                    if(datemin == 0 && $("#arrival").val() != "" && $("#leaving").val() != "") {
                        return true;
                    } else {
                        // setup the variables and check if the Data is in range.
                        start = moment(start, "DD.MM.YYY");
                        end = moment(end, "DD.MM.YYY");
                        datediff = moment.duration(end.diff(start)).asDays() +1;
                        // Check the Date if is in the same month.
                        if(arrivalMonth == leavingMonth) {
                            // Check the Date Day if is lower or higer than the given Day.
                            if(arrivalDay < leavingDay && datediff >= datemin) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            if(arrivalMonth < leavingMonth && datediff >= datemin) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            
                /**
                * FUN: Marks all cells between the start Date & the End Date.
                */
                function cellsColorMarked(start_Date, end_Date) {
                    var td = $("#" + start_Date.toISOString().substring(0, 10));
                    while(start_Date < end_Date) {
                        // Check if start_date is first add class booked-new.
                        if(start_Date.toISOString() == start_Date.toISOString()) {
                            td.find("ul li:last-child span").addClass("booked-new");
                        }
                        // Update the Date from the td.
                        start_Date.setDate(start_Date.getDate() + 1);
                        // Get the td from the Table.
                        td = $("#" + start_Date.toISOString().substring(0, 10));
                        // span new booking.
                        var ul = td.find("ul");
                        // Check if start_date is not same as end_date.
                        if(start_Date.toISOString() < end_Date.toISOString()) {
                            // Add the New Event, Booking.
                            ul.append("<li><span style=\"border-color: "+color+";\" class=\"booked-new\"></span></li>");
                        }
                    }
                    return true;
                }
            
                /**
                * FUN: Get the current date from the td Table cell.
                */
                function getDate(td) {
                    // Get the current Date from the id of the clicked td field.
                    var cal = $(td).attr("id");
                    var year = cal.match(/\d+/);
                    var table = td.closest("table");
                    var month = table.attr("id").match(/\d+/) - 1;
                    // Create an new Date object and return it.
                    var date = new Date(Date.UTC(year, month, td.text(), 0, 0, 0, 0));
                    return date;
                }
            
                /**
                * FUN: Remove and Clear all li points if there is an New Booking on it.
                */
                function clear_li() {
                    $("td ul li").each( function() {
                        if($(this).find("span").hasClass("booked-new-start") || $(this).find("span").hasClass("booked-new") || $(this).find("span").hasClass("booked-new-end")) {
                            $(this).remove();
                        }
                    });
                    // Clear all items.
                    $("#arrival").attr("value", "");
                    $("#leaving").attr("value", "");
                }
        
                /**
                * FUN: Delete selected Date if Click is outside of Calendar and Date is only firstdate.
                */
                $(document).on("click", function(event) { 
                    $target = $(event.target);
                    // Check if the clicked element is not the form submit button or an td cell.
                    if(!$target.hasAttr("id")) {
                        // Check if the element has an new event, booking inside the span.
                        if(!$target.closest("td").length && $("#arrival").val() != "" && firstClick == false || datemin == 0 && !$target.closest("td").length && $("#arrival").val() != "") {
                            clear_li();
                            firstClick = true;
                        }
                    }
                    // Clear all view Buttons if the click is on the DOM.
                    if(!$target.is("button") && !$target.is("input") && !$target.is("i") && !$target.closest("td").children("span:last-child").hasClass("book_able")) {
                        // Get the current date.
                        var date = new Date();
                            $("#year_view").attr("value", date);
                            $("#month_view").attr("value", date);
                            $("#week_view").attr("value", date);
                            $("#day_view").attr("value", date);
                    }
                });
            
                /**
                * FUN: Check the form submit before send to php script.
                */
                $("#booking_calendar_form").submit(function() {
                    if($("#arrival").val() != "" && $("#leaving").val() != "") {
                        if(checkDate()) {
                            return true;
                        } else {
                            cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_4"][$this->lg].' "+datemin, "fas fa-bell");
                            $("#arrival").css({
                                "border-color": "#ff0505"
                            });
                            $("#leaving").css({
                                "border-color": "#ff0505"
                            });
                            return false;
                        }
                    } else {
                        cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_6"][$this->lg].' "+datemin, "fas fa-bell");
                        $("#arrival").css({
                            "border-color": "#ff0505"
                        });
                        $("#leaving").css({
                            "border-color": "#ff0505"
                        });
                        return false;
                    }
                });

                /**
                 * FUN: Check the Persons val if there is an second_person.
                 */
                $("[id=\"persons\"]").change(function() {
                    var second_prcie = $("#second_price").attr("value");
                    var second_person = $("#second_person").attr("value");
                    // Prüft die Anzahl an Personen.
                    if($(this).val() > second_person) {
                        cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_5"][$this->lg].' "+second_prcie+"", "fas fa-bell");
                    }
                });

                /**
                 * FUN: Add an click funktion on the ul list.
                 */
                $(".month table tbody td .click_able_ul").on("click", function () {
                    var list = "<ul class=\'list-group\'>";
                    $(this).find("li span").each(function() {
                        var span = $(this).attr("data-original-title").replace(/<br>/g, "+").split(/[\n+]/);
                        list += "<li class=\'list-group-item\'>"+span[0]+"<span class=\'list-title\'>"+span[2]+"</span><span class=\'list-desc\'>"+span[3]+"</span><span class=\'list-date\'>"+span[5]+"</span></li>";
                    });
                    list += "</ul>";
                    $("#ulModal").modal("show");
                    $("#ulModal").on("shown.bs.modal", function() {
                        $("#ulModal .modal-body").html(list);
                    });
                });
                $("#ulModal").on("hide.bs.modal", function() {
                    $("#ulModal .modal-body").html("");
                });

                // Set an Interval to the DOM.
                '.($this->live_time ? "setInterval(live_time_change, timer);" : "").'
            });

            /**
             * FUN: Get an link to an Calendar from the cms.
             */
            function create_link(link, element) {
                var formData = new FormData();
                    formData.append("action", "getLinkById"),
                    formData.append("system", link),
                    formData.append("id", $(element).attr("data-id"));

                // Get the current Event as an link to an Calendar that the User has taken.
                $.ajax({
                    url: "./src/cms.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        cms.showNotification("2", "top", "center", "'.$this->json_lg["notify_8"][$this->lg].'", "fas fa-bell", response);
                    },
                    fail: function() {
                        cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_9"][$this->lg].'", "fas fa-bell");
                    }
                });
            }
                
            /**
             * FUN: Change the Viewport from the cms by the given view.
             */
            function create_view(view, element) {
                var formData = new FormData();
                    formData.append("action", "getCMSview"),
                    formData.append("view", view),
                    formData.append("start_date", element.value);

                // Get the current Event as an link to an Calendar that the User has taken.
                $.ajax({
                    url: "./src/cms.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $("#cms_calendar").fadeOut(500).replaceWith(response).fadeIn(500);
                        cms.showNotification("2", "top", "center", "'.$this->json_lg["notify_10"][$this->lg].'", "fas fa-bell");
                    },
                    fail: function() {
                        cms.showNotification("4", "top", "center", "'.$this->json_lg["notify_11"][$this->lg].'", "fas fa-bell");
                    }
                });
            }

            /**
             * FUN: Set the live Time change event.
             */
            function live_time_change() {
                // Get all days from table.
                $("tr td div span.active-time").each(function () {
                    $(this).removeClass("active-time").next("div").find("span").addClass("active-time");
                });
                // Set an Interval to the DOM.
                timer = getLiveTime();
            }

            /**
             * FUN: Return the current live Time to change it (by view).
             */
            function getLiveTime() {
                var date = new Date(),
                dateNow = String(date.getMinutes()).padStart(2, "0");
                tempDate = (60 - dateNow) - '.$this->time_split.';
                
                // Check if the `tempDate` is bigger than `$time_split`.
                if(tempDate > '.$this->time_split.') {
                    // Minimanize the `tempDate` by `$time_split`.
                    while (tempDate >= '.$this->time_split.') {
                        tempDate -= '.$this->time_split.';
                    }
                }
                else {
                    tempDate = 60 - dateNow;
                }
                //  Return the Time.
                return tempDate * 60 * 1000;
            }
        </script>';
    }

    /**
     * FUN: Create the Satic Modal with some informations about the Calendar.
     */
    protected function _drawStaticModal() {
        $statics = "";
        // Check if there is an active Static info to print it out.
        foreach($this->static_infos as $key => $value) {
            if($key == "events" && $value) {
                $statics .= "<p>{$this->json_lg["infos_1"][$this->lg]}: {$this->allBookings}</p>";
            }
            else if($key == "authors" && $value) {
                $statics .= "<p>{$this->json_lg["infos_2"][$this->lg]}: {$this->getKey('authors', ['name'])} <a href=\"mailto:{$this->getKey('authors', ['email'])}\">{$this->getKey('authors', ['email'])}</a></p>";
            }
            else if($key == "version" && $value) {
                $statics .= "<p>{$this->json_lg["infos_3"][$this->lg]}: ".$this->version."</p>";
            }
            else if($key == "language" && $value) {
                $statics .= "<p>{$this->json_lg["infos_4"][$this->lg]}: {$this->lg}</p>";
            }
            else if($key == "theme" && $value) {
                $statics .= "<p>{$this->json_lg["infos_5"][$this->lg]}: {$this->theme['theme']}</p>";
            }
            // Check if there is an Dev key.
            if($key == "dev" && $value) {
                $statics .= "<p>DEV:<ul>";
                // loop trought all keys from the whole CMS System.
                foreach($this as $Ckey => $row) {
                    // Check if the value is not Null and is not an array.
                    if(!is_array($row) && $row != "") {
                        $statics .= "<li>{$Ckey}: {$row}</li>";
                    }
                    // Check if the the `$row` is an array.
                    else if(is_array($row)) {
                        // loop trought the `$row` array.
                        foreach($row as $Dkey => $val) {
                            // Check if the value is not Null and is not an array.
                            if(!is_array($val) && $val != "") {
                                $statics .= "<li>{$Dkey}: {$val}</li>";
                            }
                        }
                    }
                }
                $statics .= "</ul></p>";
            }
        }
        // Print out the Static Modal with all Inforamations from .json file.
        echo '<div class="modal fade" id="staticModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticModalLabel">'.$this->json_lg["infos"][$this->lg].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            '.$statics.'
                        </div>
                    </div>
                </div>
            </div>';
    }

    /**
     * FUN: Create the Booking Modal with all Bookings in the current ul.
     */
    protected function _drawUlModal() {
        // Print out the Booking Modal with all Inforamations from ul.
        echo '<div class="modal fade '.$this->theme["theme"].'" id="ulModal" tabindex="-1" role="dialog" aria-labelledby="ulModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ulModalLabel">'.$this->json_lg["ul"][$this->lg].'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>';
    }

    /**
     * FUN: Get an Protected Key fom the CMS to an gloabl call.
     */
    public function getProtected($var, $type = "count") {
        // Check with type it is.
        switch($type) {
            case 'count':
                $RS = count($this->$var);
                break;
            case 'return':
                $RS = $this->$var;
                break;
            case 'function':
                // switch trought the array.
                switch($var[0]) {
                    case 'showCalendar':
                        $this->view = $var[1][0];
                        $this->showCalendar($var[1][1], $var[1][2]);
                        break;
                    case 'getFunction':
                        $this->getFunction($var[1][0], $var[1][1]);
                        break;
                    case 'getDateTime':
                        $RS = $this->getDateTime($var[1][0], "", "", "", $var[1][1]);
                        break;
                    case 'EVENT':
                        // Check if my_events is not null.
                        if(empty($this->my_events)) {
                            $RS = new CMS_Settings($this->EVENTS[($var[1] - 1)]);
                        } else {
                            $RS = new CMS_Settings($this->my_events[($var[1] - 1)]);
                        }
                    break;
                }
                break;
            default:
                $RS = false;
                break;
        }
        // Return the result.
        return isset($RS) ? $RS : true;
    }
    
    /**
     * FUN: Check if there is an Update available from GitHub.
     */
    protected function checkUpdate(): bool {
        // Setup all variables we need.
        $RS = array(true);

        // Check if the file from GitHub is not null.
        if(!empty(file($this->GitHub_url)[12])) {
            // Get the current version from GitHub.
            $GitHub_version = trim(preg_split("/[:at]/", file($this->GitHub_url)[12])[3]);
            
            // Check if the `$GitHub_version` is not null.
            if(!empty($this->only_string($GitHub_version))) {
                // Check if there is an update available.
                if($GitHub_version > $this->version) {
                    // There is an Update, Get the conntent from the GitHub file.
                    $msg = file_get_contents($this->GitHub_url);
                    // Write the content from the file into this file.
                    $file = fopen(dirname(__FILE__)."/cms.php", "w");
                    
                    // Add the New content to the File.
                    if(fwrite($file, $msg)) {
                        // Check if the Update is successfully installed.
                        if($this->checkUpdate() && !isset($_SESSION["checkUpdate"])) {
                            fclose($file);
                            array_push($RS, true);
                            $_SESSION["checkUpdate"] = true;
                        }
                    }
                    fclose($file);
                }
            }
        }
        // Check if `$RS` is true.
        if(!isset($RS[1])) {
            array_push($RS, false);
        }
        // There is no Update or an Error on the Update.
        return count($RS) == array_sum($RS) ? true : false;
    }

    /**
     * FUN: Display an Notify.
     */
    protected function notify($notify = "") {
        // Check the $notify wich notify it is.
        if(isset($this->json_lg[$notify][$this->lg])) {
            $this->status_msg = $this->json_lg[$notify][$this->lg];
        } else {
            $this->status_msg = $notify;
        }
        // Display the Notify Message.
        echo "<script>
            $(document).ready(function() { cms.showNotification('1', 'top', 'center', \"".$this->status_msg."\", 'fas fa-bell'); });
        </script>";
    }

    /**
     * FUN: Log the Status of an Succes or an Error Msg if the User has trund of the Display log function.
     */
    protected function status_log($error = "", $class = null, $fun = null, $line = null) {
        // Check if error is an array.
        if(is_array($error)) {
            if(isset($this->json_lg[$error[0]][$this->lg])) {
                $this->status_msg = $this->json_lg[$error[0]][$this->lg]." value: ".$error[1];
            } else {
                $this->status_msg = $error[0]." value: ".$error[1];
            }
        } else {
            // Check the $error wich error it is.
            switch($error) {
                case 'check_lg':
                    $this->status_msg = 'Please check the given language the language dosn`t exist. or the language is not activated.';
                    break;
                default:
                    if(isset($this->json_lg[$error][$this->lg])) {
                        $this->status_msg = $this->json_lg[$error][$this->lg];
                    } else {
                        $this->status_msg = $error;
                    }
                    break;
            }
        }
        // Log the Succes ror Error in the file.
        if(file_exists(dirname(__FILE__)."/logs.txt")) {
            // Create the New content for the log file.
            $msg = "File: ".dirname(__FILE__).".php           CLASS: ".$class."           FUN: ".$fun."           LINE: ".$line."           MSG: ".$this->status_msg."           Time: ".date('d.m.Y H:i:s')."\r\n";
            // Add the New content to the File
            file_put_contents(dirname(__FILE__)."/logs.txt", $msg, FILE_APPEND);
        } else {
            // Create the log file if is not existing.
            $file = fopen(dirname(__FILE__)."/logs.txt", "w");
            // Create the New content for the log file.
            $msg = "File: ".dirname(__FILE__).".php           CLASS: ".$class."           FUN: ".$fun."           LINE: ".$line."           MSG: ".$this->status_msg."           Time: ".date('d.m.Y H:i:s')."\r\n";
            // Add the New content to the File.
            fwrite($file, $msg);
            fclose($file);
        }
        // Exit the script.
        exit();
    }

    /**
	 * FUN: Unset all variables and files.
	 */
	public function destroy() {
		foreach($this as $key => $value) {
            unset($this->$key);
		}
    }
    
    /**
	 * FUN: Default destructor cleanup all variables.
	 */
	public function __destruct() {
        $this->destroy();
	}
}

/**
 * CLASS: Create CMS_Settings class.
 * 
 * Set Data from Database in to the Functions.
 */
class CMS_Settings {
    /**
     * Holds the `id` from the current Event, Booking.
     *
     * @var int
     */
    protected $id;

    /**
     * Holds the `Name` from the current Event, Booking.
     *
     * @var string
     */
    protected $event_name;

    /**
     * Holds the `start_date` from the current Event, Booking.
     *
     * @var DateTime
     */
    protected $start_date;

    /**
     * Holds the `end_date` from the current Event, Booking.
     *
     * @var DateTime
     */
    protected $end_date;

    /**
     * Holds the `my_description` from the current Event, Booking.
     *
     * @var string
     */
    protected $my_description;

    /**
     * Holds the `status` from the current Event, Booking.
     *
     * @var int
     */
    protected $status;

    /**
     * Holds the `test_event` from the current Event, Booking.
     *
     * @var int
     */
    protected $test_event;

    /**
     * This array holds all Variables from the $this->`tooltip_functions` array().
     *
     * @var array
     */
    protected $_methods = array();

    /****************************************************************
    * INFO: inizilaize the class by constructor 
    *****************************************************************/
    public function __construct($event = array()) {
        // Check if the event variable is not null.
        if($event != null) {
            // Set all variables.
            foreach((array) $event as $key => $value) {
                switch($key) {
                    case 'start_date':
                        $this->start_date = DateTime::createFromFormat("d/m/Y H:i:s", date("d/m/Y H:i:s", strtotime($value)), new DateTimeZone("UTC"));
                        break;
                    case 'end_date':
                        $this->end_date = DateTime::createFromFormat("d/m/Y H:i:s", date("d/m/Y H:i:s", strtotime($value)), new DateTimeZone("UTC"));
                        break;
                    default:
                        // Check if the key is existing in the CMS_Settings.
                        if(property_exists($this, $key)) {
                            $this->$key = $value;
                        } else {
                            $this->_methods[$key] = $value;
                        }
                        break;
                }
            }
        }
        // Insert the current status of the Event, Booking.
        $dateNow = DateTime::createFromFormat("d/m/Y H:i:s", date("d/m/Y H:i:s"), new DateTimeZone("UTC"));
        if($this->start_date <= $dateNow && $this->end_date >= $dateNow) {
            $this->status = 1; // Aktiv.
        }
        else if($this->start_date > $dateNow && $this->end_date > $dateNow) {
            $this->status = 2; // Offen.
        }
        else if($this->start_date < $dateNow && $this->end_date < $dateNow) {
            $this->status = 3; // Beendet.
        }
    }

    /****************************************************************
    * INFO: Inizilaize all Functions and set data to object (this).
    *****************************************************************/
    /**
     * FUN: Get the Current ID from Event, Booking.
     */
    public function getID(): int {
        return $this->id;
    }

    /**
     * FUN: Get the Current Start date from Event, Booking.
     */
    public function getStart_date(): DateTime {
        return $this->start_date;
    }

    /**
     * FUN: Get the Current End date from Event, Booking.
     */
    public function getEnd_date(): DateTime {
        return $this->end_date;
    }

    /**
     * FUN: Get the Current Status from Event, Booking.
     */
    public function getStatus(): int {
        return $this->status;
    }

    /**
     * FUN: Get the Current Test Status from Event, Booking.
     */
    public function getHidden(): int {
        return $this->test_event;
    }

    /**
     * FUN: Get the Current Name from Event, Booking.
     */
    public function getEvent_name(): string {
        return $this->event_name;
    }
    
    /**
     * FUN: Get the Current Description from Event, Booking.
     */
    public function getEvent_desc(): string {
        return $this->my_description;
    }

    /**
     * FUN: Get the Current Tooltip function from Event, Booking.
     */
    public function getMethod($METHOD): string {
        return isset($this->_methods[$METHOD]) ? $this->_methods[$METHOD] : "";
    }

    /**
     * FUN: Get an Protected Key fom the CMS_Settings to an gloabl call.
     */
    public function getProtected($var, $type = "count") {
        // Check with type it is.
        switch($type) {
            case 'return':
                $RS = $this->$var;
                break;
            default:
                $RS = false;
                break;
        }
        // Return the result.
        return isset($RS) ? $RS : true;
    }
}

/**
 * CLASS: Create CMS_Ajax class.
 * 
 * Set Data from Database in to the Functions.
 */
class CMS_Ajax {
    /****************************************************************
    * INFO: inizilaize the class by constructor 
    *****************************************************************/
    public function __construct() {
        // First of all we need an Session for the CMS Script!
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * FUN: Get all Events, Bookings as Table for the current view.
     */
    public function getCMSview($view = "year_view", $start_date) {
        $_SESSION["CMS"]["ajax"] = true;

        // Get all Events, Bookings from Database.
        $CT = new CMS($_SESSION["CMS"]);
        $tempDate = $CT->getProtected(["getDateTime", [$start_date, "event_end"]], "function");

        // Check if there are Events. Bookings in the my_events array.
        $CT->getProtected(["showCalendar", [$view, $tempDate->format("Y"), $tempDate->format("m")]], "function");
    }

    /**
     * FUN: Create an link to add the current Event to the given Calendar.
     */
    public function getLinkById($post) {
        $_SESSION["CMS"]["ajax"] = true;

        // Get all Events, Bookings from Database.
        $CT = new CMS($_SESSION["CMS"]);
        $EVENT = $CT->getProtected(["EVENT", $post["id"]], "function");

        // Generate the link and return the result.
        return $this->create_link($post["system"], $EVENT);
    }

    /**
    * FUN: Generate the link by given System.
    */
    protected function create_link($system, $EVENT): string {
        // Check witch System it is for the correct link.
        switch($system) {
            case 'google':
                $url = 'https://calendar.google.com/calendar/render?action=TEMPLATE';
                    $url .= '&text='.urlencode($EVENT->getEvent_name());
                    $url .= '&dates='.$EVENT->getStart_date()->format("Ymd\THis").'/'.$EVENT->getEnd_date()->format("Ymd\THis");
                    $url .= '&ctz='.$EVENT->getStart_date()->getTimezone()->getName();
                    $url .= '&details='.urlencode($EVENT->getEvent_desc());
                    $url .= '&sprop=&sprop=name:';
                return $url;
                break;
            case 'ical':
                $url = [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'BEGIN:VEVENT',
                    'UID:'.(md5($EVENT->getStart_date()->format(DateTime::ATOM).$EVENT->getEnd_date()->format(DateTime::ATOM).$EVENT->getEvent_name())),
                    'SUMMARY:'.$EVENT->getEvent_name(),
                ];
                $url[] = 'DTSTART;TZID='.$EVENT->getStart_date()->format("e:Ymd\THis");
                $url[] = 'DTEND;TZID='.$EVENT->getEnd_date()->format("e:Ymd\THis");
                $url[] = 'DESCRIPTION:'.(addcslashes($EVENT->getEvent_desc(), "\r\n,;"));
                $url[] = 'END:VEVENT';
                $url[] = 'END:VCALENDAR';
                $redirectLink = implode('%0d%0a', $url);
                return 'data:text/calendar;charset=utf8,'.$redirectLink;
                break;
            case 'yahoo':
                $url = 'https://calendar.yahoo.com/?v=60&view=d&type=20';
                    $url .= '&title='.urlencode($EVENT->getEvent_name());
                    $url .= '&st='.$EVENT->getStart_date()->format('Ymd\THis').'Z';
                    $url .= '&et='.$EVENT->getEnd_date()->format('Ymd\THis').'Z';
                    $url .= '&desc='.urlencode($EVENT->getEvent_desc());
                return $url;
                break;
            case 'webOutlook':
                $url = 'https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent';
                    $url .= '&startdt='.$EVENT->getStart_date()->format("Ymd\THis");
                    $url .= '&enddt='.$EVENT->getEnd_date()->format("Ymd\THis");
                    $url .= '&subject='.urlencode($EVENT->getEvent_name());
                    $url .= '&body='.urlencode($EVENT->getEvent_desc());
                return $url;
                break;
            default:
                return false;
                break;
        }
    }
}
?>