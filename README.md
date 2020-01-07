![CMS](cms.png)
# CMS a Full-Featured Calendar Managment System made with PHP by Florian L&auml;mmlein
#### Creates an interactive Calendar with User Settings.
#### Calendar can include Bookings, Reservations or waht you want
&nbsp;
&nbsp;
***
## CMS Features
- Calendar with Date picker.
- Easy to use, super fast and smoth.
- Multi language support.
- Show Custom Months.
- Public/Private Events.
- Multi SQL support.
- And Much more!
&nbsp;
&nbsp;
***
## How to use
```php
<?php
  // 1: Set an Session for the CMS Script
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

  // 2: include the CMS Script
    include_once "src/cms.php";
  
  // 3: Initialise the CMS Script with all Settings you want
    $CT = new CMS(
        [
          "lg"=>"en",
          "time_format"=>"H.i",
          "date_format"=>"d.m.Y",
          "is_book_able"=>false,
          "static_infos"=>[
              "active"=>true,
              "authors"=>true,
              "version"=>true
          ]
        ]
    );
  
  // 4: Set an Year and Month
    $year = isset($_GET["year"]) ? $_GET["year"] : date("Y");
    $month = isset($_GET["month"]) ? $_GET["month"] : date("m");
    
  // 5: Display the CMS
    $CT->showCalendar($year, $month);
?>
```
&nbsp;
&nbsp;
***
## How to install
1. #### You need the following files.
    ###### CMS
      - cms.php
      - langauge.json
    ###### JS
      - jquery-3.3.1.min.js
      - popper.min.js
      - bootstrap.min.js
      - bootstrap-notify.js
      - moment.min.js
    ###### CSS
      - bootstrap.min.css
&nbsp;
&nbsp;
***
## All Settings in the CMS System
| <strong style="color: #6ba0ff;">Variable</strong> | <strong style="color: #6ba0ff;">Description</strong> | <strong style="color: #6ba0ff;">Type</strong> | <strong style="color: #6ba0ff;">Default value</strong> |
| --- | --- | --- | --- |
| `lg` | Language (`my_months` also must be the same language) | string | de |
| `date_format` | Date formate for all Date Displays in the CMS System | string | Y-m-d |
| `time_format` | Time formate for all Time Displays in the CMS System | string | H:i:s |
| `min_year` | Min Year of CMS only the YEAR | string | 2019 |
| `max_year` | Max Year of CMS only the YEAR | string | 2030 |
| `cur_year` | The current Year of CMS only the YEAR | string | 2019 |
| `view` | The current view of CMS ('year_view', 'month_view', 'week_view', 'day_view', 'list_view') | string | year_view |
| | | | |
| `min_days` | Min Nights / Days for the Apartment, Room, Event | int | 0 |
| `max_events_per_day` | Maximum Events per Day (`show_more_events` Must be true) | int | 3 |
| `hidden_months` | Hidde Months by index (3 == after the 3 Month hidde all Months) | int | 13 |
| | | | |

| `season_check` | Year season check print out Seasonname and Theme | bool | false |
| `rtl_check` | RTL Support for the Language and the Calendar | bool | false |
| `weekend_check` | Show each weekend of month true or flase | bool | false |
| `auto_size` | Set auto size by the screen width/Height true or flase | bool | false |
| `back_days` | Back Days (29, 30, 01, 02) | bool | false |
| `hidde_events` | Hidde Bookings or Events (if `test_event` in Database is `1`) | bool | false |
| `events_check` | Bookings, Resevations or Events (Aktivate if you have one) | bool | false |
| `database_check` | Get all Events from the Created Database if the Database dosn´t exist | bool | false |
| `success_log` | Shows the Success from the Script | bool | false |
| `error_log` | Shows the Errors from the Script | bool | false |
| `is_book_able` | Set status of the cms.js Script if the Calendar is Bookable | bool | false |
| `show_more_events` | Show more Events, Bookings per Day if there are more than `max_events_per_day` on this Day | bool | false |
| `tooltip` | Show an Tooltip foreach Event, Booking | bool | false |
| | | | |
| `tooltip_functions` | Tooltip text (Function Names from Settings class) (firstname) | array() | null |
| `my_events` | You owne Bookings or Events (`database_check` must be false to work) | array() | null |
| `my_months` | Display custom months ["June", "July",...] (`hidden_months` will not longer work) | array() | null |
| `header` | Show all Navi Buttons on the position you want [`left` => [], `center` => [], `right` => [], `url` => ''] | array() | null |
| `event_form` | Show Booking, Reservation Form [`active`, `action`, `modal`, `arrivel_time`, `leaving_time`, `person_check`, `payment_check`, `active_event`] | array() | null |
| `static_infos` | Show some information of the CMS [`active`, `author`, `events`, `version`, `language`, `theme`] | array() | null |
| `actions_form` | Show some information of the CMS [`active`, `iCal`, `google`, `yahoo`, `webOutlook`] | array() | null |
| `sql_infos` | SQL infos [`HOST` => 'localhost', `DATABASE` => 'cms', `USER` => 'root', `PASSWORD` => '', `Type` => 'MySql'] | array() | null |
| `theme` | Theme of the CMS [`theme` => 'Original', `custom_url` => ''] | array() | null |

&nbsp;
&nbsp;
***
## Details about the Settings arrays
| <strong style="color: #6ba0ff;">Array</strong> | <strong style="color: #6ba0ff;">Parameter</strong> | <strong style="color: #6ba0ff;">Type</strong> | <strong style="color: #6ba0ff;">Description</strong> | <strong style="color: #6ba0ff;">Default value</strong> |
| --- | --- | --- | --- | --- |
| `sql_infos` | `HOST` | string | Server adress | localhost |
|             | `USER` | string | Database User | root |
|             | `DATABASE` | string | Database Name | cms |
|             | `PASSWORD` | string | Database Password | "" |
|             | `Type` | string | SQL format (mysql, mssql, odbc) | mysql |
| | | | | |
| `header` | `prev_year` | bool | Displays an previus year Button | false |
|          | `next_year` | bool | Displays an next year Button | false |
|          | `prev_month` | bool | Displays an previus month Button | false |
|          | `next_month` | bool | Displays an next month Button | false |
|          | `today` | bool | Displays an today Button | false |
|          | `year` | bool | Displays an year Button | false |
|          | `week` | bool | Displays an week Button | false |
|          | `day` | bool | Displays an day Button | false |
|          | `url` | string | The folder location of the cms script | "" |
| | | | | |
| `event_form` | `active` | bool | Activate the Event, Booking form | false |
|              | `action` | string | Action link in the HTML form | "" |
|              | `modal` | bool | Display the form as modal| false |
|              | `arrivel_time` | string | Time for arraivel an Event, Booking | 14:00:00 |
|              | `leaving_time` | string | Time for leaving an Event, Booking | 10:00:00 |
|              | `person` | bool | Active Persons on an Event, Booking | false |
|              | `payment` | bool | Active Payments on an Event, Booking | false |
|              | `active_event` | array() | Holds all Events, Bookings as an select options | null |
| | | | | |
| `static_infos` | `active` | bool | Activate infos about the current cms | false |
|                | `author` | bool | Author from cms | false |
|                | `events` | bool | Events as count | false |
|                | `version` | bool | Current Version | false |
|                | `language` | bool | Current Language | false |
|                | `theme` | bool | Current Theme | false |
| | | | | |
| `actions_form` | `active` | bool | Activate actions in the current cms | false |
|                | `iCal` | bool | iCal Download button | false |
|                | `google` | bool | google Download button | false |
|                | `yahoo` | bool | yahoo Download button | false |
|                | `webOutlook` | bool | webOutlook Download button | false |
| | | | | |
| `theme` | `theme` | string | The current Theme (Horizon, Metro, Original, Jquery) | Original |
|         | `custom_url` | string | Here you can add your one Theme from an php file | "" |

&nbsp;
&nbsp;
***
## Legacy versions
This version of the CMS are the acctual stable version which is compatible with PHP 5.3+ and is supported for feature updates.

## Do you have an Ideas, Changes or Bugs ?
Please let me know in the Comments, i will try to fix or add waht you found/want :D