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
            "Authors", "Version"
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

## Legacy versions
CMS version 1.0.0 are the acctual stable version which is compatible with PHP 5.0 - 7.0 and is supported for feature updates.

## Do you have an Ideas, Changes, Bugs or Errors ?
Please let me know in the Comments, i will try to fix or add waht you found/want :D
