隐私保护
=============

概述
---------
    当前访问者（viewer）  要访问某个用户（spaceOwner）空间下的某个对象（object） ，如果对象的主人(objectOwner)对该对象
    设置了隐私， 那么当前访者在对对象进行某种动作时（view comment rate like favorite...）会应用(apply)隐私设置的。


实现考虑
---------
    对象可以对多种动作设置隐私 比如评论，查看，投票... 每种动作可能对应可以设置不同的用户组（public friend self custom...）
    这是通用功能 但要全部实现比较耗时间

     简单起见只实现view的隐私功能 其余的应该都类似

