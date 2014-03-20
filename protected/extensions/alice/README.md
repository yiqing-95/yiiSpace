alice
=====

taobao alice ui

所有使用方式同 yiistrap
别名配置：
    ~~~
      'aliases' => array(

            // alice ui  configuration
            'alice' => 'ext.alice', // change if necessary
     ),

    ~~~
应用组件配置：   在components 配置段：
~~~

    'alice' => array(
        'class' => 'alice.components.AliceApi',
    ),
~~~
其余用法参考 docs目录 各文件
