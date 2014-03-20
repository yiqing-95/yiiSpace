                            YII
                  =========================

设计时与运行时
-----------------------------
- 静态配置 跟 运行时行为注入  比如CBehavior 的子类一般都具备静态配置跟对象注入attacheBehavior的特点
  你可以自由选择！

  行为在运行时动态注入 比较少见 感觉这个可以实现DCI （Data Context Interchange）

- 对于模型关系问题 比如一对一 一对多 多对多 这种对象关系 一般在设计期配置好了

  但对于插件型系统 关系可能后期才知道 这时在去改对象关系的配置有点尴尬 比如User 类  它可能是其他人设计的 其relation 方法
  可能就配置了profile 关系 （一对一 has_one 关系！）  在类似sns系统中 博客模块后期安装的  那么一对多关系user模块的设计者
  不可能提前预知（假设不是一个人）或者知道可能存在某些关系的注入 但不确定是什么而已 那么在这种设计中可能relations方法中
  返回的数组 可能是经过读取文件 或者Db cache等存储设备来查询到的 这样其他module在安装时 添加关系！