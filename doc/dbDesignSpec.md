数据库表设计规约：
    ====================



    常用字段命名：
    -----------
        order:  表示顺序   常见于某个对象的显示或者出现顺序上 虽然是sql关键字 但无妨大碍；
        desc:   描述（description）的缩写 ；
        is_on:  是否开启  1为开启 0为关闭  布尔类型即可；
        is_delete: 标记位 用来标记某个实体是否被删除
        active: 活动的 同"is_on" 含义；
        [enabled|disabled] :  是否启用 不建议用“否定”形式作为表字段名
        visible: 可见的 布尔型  某实体是否可见 ；
        type:   类型 用于"表继承"设计中 分表时可以此为“key”来分
        owner： 表示实体的拥有者；
        xx_cnt: 表示某个统计的数据量 也可以用xx_count 作为字段；
        status: 表示实体的状态 有此字段的对象 最好可以用"状态机"来描述 拥有状态转换能力 常常是多于两个状态(1,0)

        allow_cmt,allow_rate,allow_view: 允许评论 ，投票，查看 ；

        deletable,editable,movable,viewable,cmt_able; 都是用来表示某个实体的是否具有某个“可操作性”

        cmt: 评论缩写；
        rcv：接收缩写
        snd：发送缩写