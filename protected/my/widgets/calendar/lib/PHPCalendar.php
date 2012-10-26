<?php
/** 
 * PHP Calendar Class
 *
 * <pre>
 * Features: 
 *           Extremely portable and easy to use.
 *           Requires no dependencies (no RDBMS, AJAX, etc).
 *           Uses a timestamp in the query string for ease.
 *           Navigation buttons to "previous" and "next" months.
 *           Always starts on 1st day of month when you change months.
 *           Automatically highlights the current day.
 *           Can take any date argument that PHP's strtotime() takes.
 *           Defaults to showing current month.
 *           Stylable with CSS. Comes with optional liquid CSS layout.
 *           Outputs as valid HTML 4.01 markup. 
 *           The calendar and nav bar come wrapped in a div for you.
 * </pre>
 *
 * @author Ryan Kulla <rkulla@gmail.com>
 * @copyright Copyright (c) 2009, Ryan Kulla
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @example example.php
 * @package PHPCalendar
 */
class PHPCalendar 
{
    /**
     * An array of months. 0, January, ... December
     * The array starts with 0, since PHP's date() function uses 1-12
     * @var array List of months 
     */
    protected $monthMap = array(0, 'January', 'February', 'March',
        'April', 'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December');

    /**
     * List of day names. Sunday through Saturday
     * @var array List of the days of the week
     */
    protected $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 
        'Thursday', 'Friday', 'Saturday');

    /**
     * The unix timestamp variable used to figure out what date it is
     * @var int Timestamp
     */
    protected $timestamp;

    /**
     * If the 1st of the month is a Sat, we'd get: Sat(1=>'Sat', 2=>'Sun', ...)
     * @var array Map of the month
     */
    protected $calArray;

    /**
     * The number of days in the given month. 28 through 31
     * @var int Number of days in the month
     */
    protected $daysInMonth;

    /**
     * The numeric representation of the month. 1 through 12
     * @var int Month number
     */
    protected $month;

    /**
     * The numeric representation of the year. 4 digits (yyyy format)
     * @var int Year number
     */
    protected $year;

    /**
     * The day the current month began on. 1 (for Mon) through 7 (for Sun)
     * @var int The day the month began 1 through 7 (for Mon through Sun)
     */
    protected $dayMonthBegan;
    

    /**
     *
     * The optional parameter $startingDate is the date you want the 
     * calendar to start with. It strtotime's the value you supply,
     * so it can take any date value that strtotime can take, such 
     * as: "March 1955", "last monday", "-5 days ago", etc.
     * @param mixed Starting date. (Optional)
     */
    public function __construct($startingDate=null) {
        // Determine the timestamp
        if (!empty($_GET['timestamp'])) {
            $this->timestamp = $_GET['timestamp'];
        } elseif ($startingDate) {
            $this->timestamp = strtotime($startingDate);
        } else {
            // Start the timestamp on the first of the month
            $this->timestamp = $this->getFirstOfMonth(time());
        }

        // Set the total number of days in the month
        $this->daysInMonth = date('t', $this->timestamp); 

        // Set the numeric represenation of the month (1 through 12)
        $this->month = date('n', $this->timestamp);


        // Sets the full numeric representation of a year, 4 digits
        $this->year = date('Y', $this->timestamp);


        // Set the day current month started. 1 (for Mon) through 7 (for Sun)
        $this->dayMonthBegan = 
            date('N', 
                strtotime($this->monthMap[$this->month] . ' ' . $this->year));

        // Make a dynamic map of the month like array(1=>'Sat', 2=>'Sun' ...)
        // If Sat is the 1st of the month, the array starts with 1=>'Sat', etc.
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            $this->calArray[$i] =
                date('D',
                    strtotime($this->monthMap[$this->month] . 
                        ' ' . $i .  ' ' . $this->year
                    )
                );
        }
    }


    /**
     * Accessor for the current timestamp
     * @return int timestamp
     */
    public function getTimestamp() {
        return $this->timestamp;
    }


    /**
     * Generates the navigation buttons that go forward or backward 
     * a month at a time.
     * @param string direction
     * @return string HTML
     */
    protected function getNavButton($direction) {
        $when = $direction == 'forward' ? '+1 month' : '-1 month';

        return '<a class="cal-nav-buttons" href="?timestamp=' .
            $this->getFirstOfMonth(
                strtotime($when, $this->timestamp)) . '">' . 
                date("M", strtotime($when, $this->timestamp)) . '</a>';
    }


    /**
     * Get the current month's full name
     * @return string current month's full name (E.g., "September")
     */
    protected function getCurrentMonthName() {
        return $this->monthMap[$this->month];
    }


    /**
     * Get the first day of the month
     * @return int|false timestamp or error
     */
    protected function getFirstOfMonth($timestamp) {
        return strtotime(date('n/01/Y', $timestamp));
    }


    /**
     * Get the calendar
     * <code>
     * date_default_timezone_set('America/Los_Angeles'); // Set locale
     * $phpCalendar = new PHPCalendar(); // Create a PHPCalendar object
     * echo $phpCalendar->getCalendar(); // Show the calendar
     * </code>
     * @return string Calendar HTML
     */
    public function getCalendar() {
        // Start the opening div that the calendar is contained within
        $cal = '<div id="cal-content">';

        // Set the navigation buttons and the current month/year heading
        $cal .= $this->getNavButton('backward') . 
            ' <span id="cal-date-heading">' .
            $this->getCurrentMonthName() . ', ' . $this->year .
            '</span> ' . $this->getNavButton('forward'); 

        // Start the HTML table that the calendar squares are in
        $cal .= '<table id="calendar" cellspacing="2">';

        // Generate the day names at top of the calendar.
        $cal .= '<tr class="days_header">';
        foreach ($this->days as $day) { 
            $cal .= '<td>' . strtoupper(substr($day, 0, 3)) . '</td>';
        }
        $cal .= '</tr>';

        // Print empty calendar squares for days the first day doesn't start on
        $cal .= '<tr>';
        if ($this->dayMonthBegan < 7) {
            for ($i = 0; $i < $this->dayMonthBegan; $i++) {
                $cal .= '<td>&nbsp;</td>';
            }
        }

        // Loop through all the days
        $dayAsInt = 0;
        foreach ($this->calArray as $dayAsStr) { 
            $dayAsInt++;

            // Highlight today
            $currentDay = date('n/' . $dayAsInt . '/Y', $this->timestamp);
            $class = '';
            if ($currentDay == date('n/j/Y')) { 
                $class = 'class="today"'; 
            } else {
                $class = '';
            }

            // Set the actual calendar squares, hyperlinked to their timestamps
            $cal .= 
                '<td><a href="?timestamp=' . strtotime($currentDay) . '"' . 
                $class . '>' . $dayAsInt . '</a></td>';

            // Our calendar has Saturday as the last day of the week,
            // so we'll wrap to a newline after every SAT
            if ($dayAsInt != $this->daysInMonth && $dayAsStr == 'Sat') {
                $cal .= '</tr><tr>';
            }
        }

        // Close up the table and div for the calendar
        $cal .= '</tr> </table> </div>';

        return $cal;
    }
}
