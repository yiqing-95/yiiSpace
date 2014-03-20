用户内容模块的设计方法
====

## 三个空间职责

    1.  siteIndex 系统主页中需要显示本内容模块中的节点列表。一般是本模块提供供系统主页嵌入的block块（widget实现）可以
        支持ajax载入 或者直接内嵌到index主页 。  如：  最近博客 最热博客 管理员推荐的博客 等都是以portlet内容块形式出现。

    2.  子站空间 按照模块可视为子站的概念 那么内容性模块还需要完成子站功能， 控制器名定为： NodeIndex  NodeHome 因为模块
        本身还有个id 通过控制器映射（controllerMap）让路由变为  NodeId/index  或者 NodeId/home
        不管是系统主页 还是子站主页 都是从不同维度来提取本模块中可供展示的内容节点：  最新 全部 最热 最多评论 推荐 投票
        最多 标签..

    3.  个人空间 用户的内容节点空间 需要浏览某个指定用户的内容节点。 list形式： 全部 ，按日期归档 ， 标签云连接 ， 用户的个人
        节点分类（如博客分类 ）控制器名定为： NodeMember 通过controllerMap使名字变为： NodeId/member/uid(重写下url吧！)

## 两个管理后台

    1.  需要系统后台来管理本模块内的所有内容 系统本身分为前后台两部分 一个module作为系统的一个子系统 也需要提供这种入口
        每个module可以分前后台管理 通过front backend 来弄成前后端形式 体现在控制器和视图分为前后端两个子目录。

    2.  用户空间由若干个内容空间内容module组成（blog photo ..） 那么会员自己在前端 还需要管理功能 这样前端其实还需要分
        public部分跟manager部分  这里使用 My 前缀来表示管理（可能还可以通过在front目录中再设admin目录来细分 但感觉层次
        太深不好理解）  如： MyNodeController  MyNodeCateController   完成  增 删 改 管理的功能

## 前台展示
   主要是list  跟  view 的实现 ！