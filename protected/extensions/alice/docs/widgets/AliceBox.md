#  Alice  box  widget
----
    用yii widget的方式 封装了alice  ui 中的 box 模块

## 使用方式

-  box 内容区域比较少：
    ~~~
             $this->widget('alice.widgets.AliceBox',array(
                 'headTitle'=>'head title',
                 'headText'=>'head text',
                 'headMore'=>'head more',

                 'content'=>'hi this is content',
             ));
    ~~~
    或者渲染局部视图
    ~~~
         $this->widget('alice.widgets.AliceBox',array(
             'headTitle'=>'head title',
             'headText'=>'head text',
             'headMore'=>'head more',

             'content'=>Yii::app()->controller->renderPartial('_somePartialView',array(....),true),
         ));
    ~~~

-  当内容区域内容比较多时 用区间输出：
    ~~~
    <?php
    $this->beginWidget('alice.widgets.AliceBox',array(
        'htmlOptions'=>array(
            // 'class'=>'ui-box-follow',
        ),
        'headTitle'=>'head title',
        'headText'=>'head text',
        'headMore'=>'head more',
    ));?>
              这里是任何 文本 ，html片段 或者其他Yii widget ..

    <?php $this->endWidget();?>
    ~~~

-   连体box  ：
    连体box 在alice 中只是添加了一个css类而已   两个box紧邻输入即可：
    ~~~
    <?php
         $this->widget('alice.widgets.AliceBox',array(
             'headTitle'=>'head title',
             'headText'=>'head text',
             'headMore'=>'head more',

             'content'=>'hi',
         ));
         // 注意下面这个box 会紧邻上面那个的  只是多了一个特殊的css类"ui-box-follow"
    $this->widget('alice.widgets.AliceBox',array(
        'htmlOptions'=>array(
               'class'=>'ui-box-follow',
        ),
        'headTitle'=>'head title',
        'headText'=>'head text',
        'headMore'=>'head more',

        'content'=>'hi this is a following box !',
    ));
    ?>

    ~~~