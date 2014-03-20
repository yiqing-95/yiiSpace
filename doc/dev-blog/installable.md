可以参考很多项目的做法
============================

现在好多优秀的cms 都支持可安装机制
 *  安装的粒度 常常是module级别
 *  但还有些可以是plugin widget

plugin的实现

 *  在joomla中plugin 好像是作为监听器实现的 在yii中可以用behavior实现  但注意事件机制的实现有很多变种：
    最原始的那种类似yii1.x系用的机制 symfony事件机制用了中心化 所有事件的监听和触发都是跟EventDispatcher交互的。
    以上两种都有要求 事件监听者需要先实例化 然后注册到注册表（不管是事件源的 或者是事件分派器的）。
    这就是经典的观察者模式

    事件实现的变种还可以扩展到网络上面 远程实现（变成 发布者/订阅者设计模式）。

    先实例化监听者的方法 比较浪费 如果事件还没触发 或者很少触发！

    延迟实例化监听器： 这是比较节省资源的方式。事件发生时 通过事件名查询 对应的监听器配置 然后实例化 逐个调用每个监听器
    的update方法 并传递事件数据（事件源 或者变更了哪些数据）。

    在yii中有几个类是通过配置behavior 来监听owner的事件的（参考ActiveRecord【ActiveRecordBehavior】）  yii中有个扩展是
    拦截所有的事件到一个公共点 然后再由此公共点统一调度。参考扩展event-interceptor
    比如某个类的behavior方法 是返回一个数组的，这里要引入变数 就可以通过方法中做类似变通：
    function behaviors(){
        return PluginManager::getBehaviors('xxx.xxx');
    }
    所有是方法的地方都可以通过此思路进行扩展！！！ 要会举一反三哦

可以参考的项目
------------------
yupe

adaptCms
*   每个可安装的插件使用一个plugin.json 来描述插件信息！这个思路很好 类似composer.json