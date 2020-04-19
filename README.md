![CMS](cms.png)
# CMS a Full-Featured Calendar Managment System made with PHP by Florian L&auml;mmlein
#### Creates an interactive Calendar with User Settings.
#### Calendar can include Bookings, Reservations or waht you want
#### The CMS System has over 80+ Settings options in the acctual stable version witch you can use.
&nbsp;
&nbsp;
***
## CMS Features
- Calendar with Date picker.
- Easy to use, super fast and smoth.
- Multi language support.
- Show Custom Months.
- Public/Private Events.
- Custom Themes.
- View Changes.
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
      - styles.css.php
&nbsp;
&nbsp;
***
## All Settings in the CMS System
| <strong style="color: #6ba0ff;">Variable</strong> | <strong style="color: #6ba0ff;">Description</strong> | <strong style="color: #6ba0ff;">Type</strong> | <strong style="color: #6ba0ff;">Default value</strong> |
| --- | --- | --- | --- |
| `STRINGS` | | | |
| `lg` | Language (`my_months` also must be the same language) | string | de |
| `date_format` | Date formate for all Date Displays in the CMS System | string | Y-m-d |
| `time_format` | Time formate for all Time Displays in the CMS System | string | H:i:s |
| `time_zone` | Time zone for all Time creations in the CMS System | string | Europe/Berlin |
| `min_year` | Min Year of CMS only the YEAR | string | 2019 |
| `max_year` | Max Year of CMS only the YEAR | string | 2030 |
| `cur_year` | The current Year of CMS only the YEAR | string | 2019 |
| `view` | The current view of CMS ('year_view', 'month_view', 'week_view', 'day_view', 'list_view') | string | year_view |
| | | | |
| `INTEGER` | | | |
| `time_split` | Split the hours on day_view, week_view into minutes (5, 10, 15, 30) | int | 60 |
| `min_days` | Min Nights / Days for the Apartment, Room, Event | int | 0 |
| `max_events_per_day` | Maximum Events per Day (`show_more_events` Must be true) | int | 3 |
| `hidden_months` | Hidde Months by index (3 == after the 3 Month hidde all Months) | int | 13 |
| | | | |
| `BOOLEAN` | | | |
| `update_check` | Check if there is an Update available from GitHub | bool | true |
| `time_change` | Yearseasons Time change check print out Season change Event | bool | false |
| `live_time` | Time change for the Week and Day view | bool | false |
| `season_check` | Yearseasons check print out Seasonname and Theme | bool | false |
| `rtl_check` | RTL Support for the Language and the Calendar | bool | false |
| `weekend_check` | Show each weekend of month true or flase | bool | false |
| `auto_size` | Set auto size by the screen width/Height true or flase | bool | false |
| `back_days` | Back Days (29, 30, 01, 02) | bool | false |
| `hidde_events` | Hidde Bookings or Events (if `test_event` is `1`) | bool | false |
| `events_check` | Bookings, Resevations or Events (Aktivate if you have one) | bool | false |
| `status_logs` | Shows the logs from the Script | bool | false |
| `is_book_able` | Set status of the cms.js Script if the Calendar is Bookable | bool | false |
| `show_more_events` | Show more Events, Bookings per Day if there are more than `max_events_per_day` on this Day | bool | false |
| `tooltip` | Show an Tooltip foreach Event, Booking | bool | false |
| | | | |
| `ARRAY` | | | |
| `tooltip_functions` | Tooltip text (Function Names from Settings class) (firstname) | array() | null |
| `my_events` | You owne Bookings or Events (`database_check` must be false to work) | array() | null |
| `my_months` | Display custom months ['June', 'July',...] (`hidden_months` will not longer work) | array() | null |
| `header` | Show all Navi Buttons on the position you want [`left` => [], `center` => [], `right` => [], `url` => ''] | array() | null |
| `event_form` | Show Booking, Reservation Form [`active`, `action`, `modal`, `arrivel_time`, `leaving_time`, `active_event`, `events`] | array() | null |
| `static_infos` | Show some information of the CMS [`active`, `author`, `events`, `version`, `language`, `theme`] | array() | null |
| `actions_form` | Show some actions as Buttons [`active`, `iCal`, `google`, `yahoo`, `webOutlook`] | array() | null |
| `theme` | Theme of the CMS [`theme` => 'Original', `custom_url` => ''] | array() | null |

&nbsp;
&nbsp;
***
## Details about the Settings arrays
| <strong style="color: #6ba0ff;">Array</strong> | <strong style="color: #6ba0ff;">Parameter</strong> | <strong style="color: #6ba0ff;">Type</strong> | <strong style="color: #6ba0ff;">Description</strong> | <strong style="color: #6ba0ff;">Default value</strong> |
| --- | --- | --- | --- | --- |
| `header` | `left` | array() | Displays all from the `header_help` on left | null |
|          | `center` | array() | Displays all from the `header_help` on center | null |
|          | `right` | array() | Displays all from the `header_help` on right | null |
|          | `url` | string | The folder location of the cms | "" |
| | | | | |
| | | | | |
| `header_help` | `next_year` | string | Displays an next year Button | "" |
|          | `prev_month` | string | Displays an previus month Button | "" |
|          | `next_month` | string | Displays an next month Button | "" |
|          | `today` | string | Displays an today Button | "" |
|          | `year` | string | Displays an year Button | "" |
|          | `week` | string | Displays an week Button | "" |
|          | `day` | string | Displays an day Button | "" |
|          | `year_view` | string | Displays an Year view Button | "" |
|          | `month_view` | string | Displays an Month view Button | "" |
|          | `week_view` | string | Displays an Week view Button | "" |
|          | `day_view` | string | Displays an Day view Button | "" |
|          | `list_view` | string | Displays an List view Button | "" |
| | | | | |
| | | | | |
| `event_form` | `active` | bool | Activate the Event, Booking form | false |
|              | `action` | string | Action link in the HTML form | "" |
|              | `modal` | bool | Display the form as modal| false |
|              | `arrivel_time` | string | Time for arraivel an Event, Booking | 14:00:00 |
|              | `leaving_time` | string | Time for leaving an Event, Booking | 10:00:00 |
|              | `active_event` | string | Select an Event, Booking from the `events` array | "" |
|              | `events` | array() | Holds all Events, Bookings you wan´t | null |
| | | | | |
| | | | | |
| `static_infos` | `active` | bool | Activate infos about the current cms | false |
|                | `author` | bool | Author from cms | false |
|                | `events` | bool | Events as count | false |
|                | `version` | bool | Current Version | false |
|                | `language` | bool | Current Language | false |
|                | `theme` | bool | Current Theme | false |
| | | | | |
| | | | | |
| `actions_form` | `active` | bool | Activate actions in the current cms | false |
|                | `iCal` | bool | iCal Download button | false |
|                | `google` | bool | google Download button | false |
|                | `yahoo` | bool | yahoo Download button | false |
|                | `webOutlook` | bool | webOutlook Download button | false |
| | | | | |
| | | | | |
| `theme` | `theme` | string | The current Theme ('Horizon', 'Metro', 'Original', 'Jquery') | Original |
|         | `custom_url` | string | Here you can add your one Theme from an php file | "" |

&nbsp;
&nbsp;
***
## Legacy versions
This version of the CMS are the acctual stable version which is compatible with PHP 5.5.3+ and is supported for feature updates.

## Do you have any Ideas, Changes or Bugs ?
Please let me know in the Comments, i will try to fix or add waht you found/want :D
