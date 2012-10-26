yii-flowing-calendar-extension
==============================

A customizable and simple-to-use Yii calendar extension

You can download this extension right from here, or find it on the Yii extensions page [here](http://yiiframework.com/extension/flowing-calendar/).

## To Use
1. Place the unzipped flowing-calendar folder into your extensions folder
2. Initialize the calendar widget where you like inside of a view file:
`$this->widget('ext.flowing-calendar.FlowingCalendarWidget');`
3. You can pass the widget arguments and specify a default month and year like so:
`$this->widget('ext.flowing-calendar.FlowingCalendarWidget', array("month"=>01, "year"=>1999));`
4. You can also add custom styling to it by writing your own css file (using the included one as a guide) and specifying your new filename with the style argument like:
`$this->widget('ext.flowing-calendar.FlowingCalendarWidget', array("month"=>01, "year"=>1999, "style"=>"newCss"));`

By using multiple CSS files and specifying the style in the widget parameters, you can have multiple calendars each styled differently, all residing on the same page!
