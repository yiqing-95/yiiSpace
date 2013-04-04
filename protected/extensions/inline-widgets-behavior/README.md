InlineWidgetsBehavior
==========================
Allows render widgets in page content in Yii Framework based projects

- [README RUS](http://www.elisdn.ru/blog/13/vstraivaem-vidjeti-v-tekst-stranici-v-yii)
- [Extension](http://www.yiiframework.com/extension/inline-widgets-behavior/)

Installation
------------

Extract to `protected/components`.

Usage example
-------------

Add allowed widgets list to `config/main.php`:
~~~
[php]
return array(
    // ...
    'params'=>array(
         // ...
        'runtimeWidgets'=>array(
            'ShareWidget',
            'Comments',
            'blog.wigets.LastPostsWidget',
        }
    }
}
~~~

Create widgets:
~~~
[php]
class LastPostsWidget extends CWidget
{
    public $tpl='default';
    public $limit=3;

    public function run()
    {
        $posts = Post::model()->published()->last($this->limit)->findAll();
        $this->render('LastPosts/' . $this->tpl, array(
            'posts'=>$posts,
        ));
    }
}
~~~

Attach behavior to main controller:
~~~
[php]
class Controller extends CController
{
    public function behaviors()
    {
        return array(
            'InlineWidgetsBehavior'=>array(
                'class'=>'application.components.DInlineWidgetsBehavior',
                'location'=>'application.components.widgets', // default path (optional)               
                'widgets'=>Yii::app()->params['runtimeWidgets'],
                'startBlock'=> '{{w:',
                'endBlock'=> '}}',
             ),
        );
    }
}
~~~

You can define global classname suffix like 'Widget':
~~~
[php]
class Controller extends CController
{
    public function behaviors()
    {
        return array(
            'InlineWidgetsBehavior'=>array(
                'class'=>'application.components.DInlineWidgetsBehavior',
                'widgets'=>Yii::app()->params['runtimeWidgets'],
                'classSuffix'=> 'Widget',
             ),
        );
    }
}
~~~

for using short names 'LastPosts' instead of 'LastPostsWidget' :
~~~
[php]
return array(
    // ...
    'params'=>array(
         // ...
        'runtimeWidgets'=>array(
            'Share',
            'Comments',
            'blog.wigets.LastPosts',
        }
    }
}
~~~

For insert widgets in content you can use string of this format in your text:
~~~
<startBlock><WidgetName>[|<attribute>=<value>[;<attribute>=<value>]]<endBlock>
~~~

For rendering widgets in any View you must call `Controller::decodeWidgets()` method for model HTML content. 

For example:
~~~
[php]
<?php $model->text = '
    <h2>Lorem ipsum</h2>
 
    <h2>Latest posts</h2>
    <p>{{w:LastPostsWidget}}</p>
 
    <h2>Latest posts (with parameters)</h2>
    <p>{{w:LastPostsWidget|limit=5}}</p>
 
    <h2>Latest posts (with inner caching)</h2>    
    <p>{{w:LastPostsWidget|limit=5;tpl=small;cache=300}}</p>
 
    <p>Dolor...</p>
'; ?>
 
<h1><?php echo CHtml::encode($model->title); ?></h1>
<?php echo $this->decodeWidgets($model->text); ?>
~~~