
开发约定
====================================

ajax 处理 (可以参考sina sohu 的ajax返回结构)
------------------------------------------

        ajax 处理: 服务端在复杂的ajax返回结构中可以采用这样的结构：
        { status : "success"|true|false,
          msg    : "deletion is complete !",
          data   : "<div>response text  </div>",
         }