#  布局

一个项目有可能使用不同的布局 只在你需要使用aliceui 的布局中引入alice 的css文件：

~~~
// 布局引入
<?php
    Yii::app()->alice->registerCoreCss();

?>
~~~

## 使用widget

需要封装alice的各种常见widget
也可以手动输出html markup区块 使用原生结构


## 集成seaJs
seaJs 自有一套目录规范 这里只是使用最简单的结构
在本目录的layout_tmeplate.php 中有跟yii的结合方式 其他规范 谨遵seaJs文档即可
主要解决jquery相关的问题：
- jquery ：   seaJs 有自己封装为CMD 规范的jquery版本  yii内置集成jquery 这样就会有两个不同版本
  我们选择只用一个版本 --- 用seaJs封装那个就好了； 这样需要我们禁用yii输出jquery了 参考模板中的做法。

- jquery插件： 修改的CMD jquery版本 会把jquery注册到全局对象windows上的， 这样其他开源标准jquery插件只是依赖了$,jQuery
   而已 只用提前引入 就可以保证所有插件能够正常使用（CListView  CGridView CActiveForm..）。

使用seaJs  可以有大量淘宝开源的UI组件可用 ，比如 阿拉蕾arale 库 gallery库 具体可参考
[阿拉蕾js官网](http://aralejs.org/docs/getting-started.html)


### seaJs 目录存放位置
官网介绍 一般会用类似这样的结构：
             statics(assets)
                    --sea-modules
                      --seajs
                      --alice
                      --gallery
                      --arale

该目录位于webroot下 可用浏览器直接访问到  在yii中assets目录一般是可以直接访问到的 但由于assets 是yii发布来自各个不同组件
，扩展 标准内置widget（gridview listview cactiveform...jui..）而拷贝而来的东西 ， 是程序自动生成的  我们不应该手动往里面
放东西 官网也出有扩展 可自动删除该目录下的所有东西 （assets以及runtime目录下的东西都是 安全 可删除的！！）；
鉴于以上原因 我们需要在webroot下引入另一个目录 用了存放seaJs规范的前端js目录  ，这个目录截图在本目录下seajsInYii.jpg里面

### 使用spm 工具安装阿里的js模块
这里是简单步骤
  1  安装 nodejs
  2  用 npm  命令安装 spm  ：   npm install spm
  3  接着 把当前工作目录转到 public目录下（cd ...）（参考seaJsInYii.jpg里面的结构）安装各中js模块
     如 spm install jquery/jquery
要熟悉阿里的目录结构：
   > Arale 的 ID 由四部分组成：{{family}}/{{module}}/{{version}}/{{file}}
  具体请参考 [http://aralejs.org/docs/getting-started.html](http://aralejs.org/docs/getting-started.html)