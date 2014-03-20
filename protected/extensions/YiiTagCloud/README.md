YiiTagCloud
===========

Simple Yii Framework Tag Cloud.


##Requirements
- Yii 1.1 or above

##Installation

- Extract 'YiiTagCloud' folder to protected/extensions/YiiTagCloud.

##Usage

~~~
$this->widget('application.extensions.YiiTagCloud.YiiTagCloud', 
            array(
                'beginColor' => '00089A',
                'endColor' => 'A3AEFF',
                'minFontSize' => 8,
                'maxFontSize' => 20,
                'arrTags' => array (
                        "MVC"     => array('weight'=> 2),
                        "PHP"     => array('weight'=> 9, 'url' => 'http://php.net'),
                        "MySQL"   => array('weight'=> 8, 'url' => 'http://mysql.com'),
                        "jQuery"  => array('weight'=> 6, 'url' => 'http://jquery.com'),
                        "SQL"     => array('weight'=> 9),
                        "C#"    => array('weight'=> 2),
                ),
          )
);
~~~

##Resources
 * [Github](https://github.com/EvandroSwk/YiiTagCloud)
 * [Try out a demo](http://www.evandroswk.com/index.php/projetos/tagCloud)