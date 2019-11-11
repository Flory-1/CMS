![CMS](cms.png)
# CMS a Full-Featured Calendar Managment System made with PHP by Florian Lämmlein
#### Creates an interactive Calendar with User Settings.
#### Calendar can include Bookings, Reservations or waht you want

## CMS Features
- Calendar view with Date range picker.
- Easy to use, super fast and smoth.
- Multi language support.
- Show Custom Months.
- And Much more!

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
        ],
        "legend"=>true
        ]
    );
  
  // 4: Set the current Year and Month
    $year = isset($_GET["year"]) ? $_GET["year"] : date("Y");
    $month = isset($_GET["month"]) ? $_GET["month"] : date("m");
    
  // 5: Display the CMS Script
    $CT->showCalendar($year, $month);
?>
```

## All Settings in the CMS System
| Variable | Description | Type | Default value |
| --- | --- | --- |
| `lg` | Language (`my_months` also must be the same language) | string | de |
| `rtl_check` | RTL Support for the Language and the Calendar | bool | false |
| `weekend_check` | Show each weekend of month true or flase | bool | false |
| `hidde_events` | Hidde Bookings, Resevations or Events true or false (if `test_event` in Database is `1`) | bool | false |
| `events_check` | Bookings, Resevations or Events true or false (Aktivate if you have one) | bool | false |
| `my_events` | Bookings, Resevations or Events as array (`database_check` must be false to work) | array() | null |
| `min_days` | Min Nights / Days for the Apartment, Room, Event | int | 0 |
| `hidden_months` | Hidde Months by index (3 == after the 3 Month hidde all Months) | int | 13 |
| `back_days` | Back Days true or false (29, 30, 01, 02) | bool | false |
| `tooltip` | Show an Tooltip foreach Event, Booking true or false | bool | false |
| `tooltip_functions` | Tooltip text (Function Names from Settings class) (firstname) | array() | null |
| `my_months` | Custom Months that are Displayed by array ["June", "July",...] (`hidden_months` will not longer work) | array() | null |
| `buttons` | Show all the Buttons you want ["active", "prev_year", "next_year", "prev_month", "next_month", "today", "year", "week", "day" "url"] | array() | null |
| `min_year` | Min Year of CMS only the YEAR | bool | false |
| `max_year` | Max Year of CMS only the YEAR | bool | false |
| `legend` | Show legend on top of the Calendar true or false | bool | false |
| `event_form` | Show Booking, Reservation Form ["active","action","modal","arrivel_time","leaving_time","person_check","payment_check","active_event"] | array() | null |
| `date_format` | Date formate for all Date Displays in the CMS System | string | Y-m-d |
| `time_format` | Time formate for all Time Displays in the CMS System | string | H:i:s |
| `static_infos` | Show some information of the CMS ["active", "author", "events", "version", "language", "theme"] | array() | null |
| `error_log` | Shows the Errors from the Script true or false | bool | false |
| `success_log` | Shows the Success from the Script true or false | bool | false |
| `database_check` | Get all Events from the Created Database if the Database dosn´t exist | bool | false |
| `show_more_events` | Show more Events, Bookings per Day if there are more than `max_events_per_day` on this Day true or false | bool | false |
| `max_events_per_day` | Maximum Events per Day (`show_more_events` Must be true) | int | 3 |
| `is_book_able` | Set status of the cms.js Script if the Calendar is Bookable true or false | bool | false |
| `theme` | Theme of the CMS (Horizon, Metro, Original) | string | Original |

## Legacy versions
CMS version 1.0.0 are the acctual stable version which is compatible with PHP 5.0 - 7.0 and is supported for feature updates.

## Do you have an Ideas, Changes, Bugs or Errors ?
Please let me know in the Comments, i will try to fix or add waht you found/want :D
