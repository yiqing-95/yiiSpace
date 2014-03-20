<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-26
 * Time: 下午2:34
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
//print_r($data);
// 这个蛋疼的组件 会影响到artdialog 很见鬼 可能里面的js修改了jquery行为
/*
$this->widget('application.extensions.morris.MorrisChartWidget', array(
    'id'      => 'myChartElement',
    'options' => array(
        'chartType' => 'Bar',
        'data'      => $data ,
        'xkey'      => 'w',
        'ykeys'     => array('times'),
        'labels'    => array('times'),
       // 'axes'=>false,
    ),
));
*/

/*
$this->Widget('ext.highcharts.HighchartsWidget', array(
    'options'=>'{
      "title": { "text": "Fruit Consumption" },
      "xAxis": {
         "categories": ["Apples", "Bananas", "Oranges"]
      },
      "yAxis": {
         "title": { "text": "Fruit eaten" }
      },
      "series": [
         { "name": "Jane", "data": [1, 0, 4] },
         { "name": "John", "data": [5, 7,3] }
      ]
   }'
));
$this->Widget('ext.highcharts.HighchartsWidget', array(
    'options'=>array(
        'title' => array('text' => 'Fruit Consumption'),
        'xAxis' => array(
            'categories' => array('Apples', 'Bananas', 'Oranges')
        ),
        'yAxis' => array(
            'title' => array('text' => 'Fruit eaten')
        ),
        'series' => array(
          //  array('name' => 'Jane', 'data' => array(1, 0, 4)),
          //  array('name' => 'John', 'data' => array(5, 7, 3))
        )
    )
));*/

?>
<?php
Yii::app()->setComponent('chartjs',array('class' => 'ext.chartjs.components.ChartJs'));
Yii::app()->getComponent('chartjs');
?>
</p>
<div>
    <?php
    $this->widget(
        'ext.chartjs.widgets.ChBars',
        array(
         //   'width' => 600,
         //   'height' => 300,
            'htmlOptions' => array(),
            'labels' => array("January","February","March","April","May","June"),
            'datasets' => array(
                array(
                    "fillColor" => "#ff00ff",
                    "strokeColor" => "rgba(220,220,220,1)",
                    "data" => array(10, 20, 30, 40, 50, 60)
                )
            ),
            'options' => array()
        )
    );
    ?>
</div>