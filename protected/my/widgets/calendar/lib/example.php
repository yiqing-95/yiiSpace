<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>PHPCalendar</title>
        <link rel="stylesheet" href="../phpcalendar.css"
              type="text/css" media="screen, projection">
    </head>
<body>


<?php
    require 'PHPCalendar.php';

    // Make sure you set the timezone to your local timezone
    date_default_timezone_set('America/Los_Angeles');

    //$php_calendar = new PHPCalendar('march 2001'); 
    $phpCalendar = new PHPCalendar(); 

    echo $phpCalendar->getCalendar();

    echo date('r', $phpCalendar->getTimestamp());
?>


</body>
</html>
