morris.js-yii
=============

[Morris.js](http://www.oesmith.co.uk/morris.js/) widget for [Yii Framework](http://www.yiiframework.com/)

Usage
=====

Place files in Yii's extension folder.

```php
$this->widget('application.extensions.morris.MorrisChartWidget', array(
    'id'      => 'myChartElement',
    'options' => array(
        'chartType' => 'Area',
        'data'      => array(
            array('y' => 2006, 'a' => 100, 'b' => 90),
            array('y' => 2007, 'a' => 40, 'b' => 60),
            array('y' => 2008, 'a' => 50, 'b' => 10),
            array('y' => 2009, 'a' => 60, 'b' => 50),
            array('y' => 2010, 'a' => 60, 'b' => 40),
        ),
        'xkey'      => 'y',
        'ykeys'     => array('a', 'b'),
        'labels'    => array('Series A', 'Series B'),
    ),
));
```

License
=======
BSD / MIT.