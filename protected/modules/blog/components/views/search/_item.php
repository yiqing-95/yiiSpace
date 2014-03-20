<?php
  // 注意数据不一致现象 需要推入高速可写的队列（redis、resque）去同步啊！

  if(empty($data->author)){
     // 用户已经删除了 但其博客还存在 ！
      return ;
  }
?>

<div class="post">
    <div class="title">
        <h2><?php echo CHtml::link(
                CHtml::encode($data->title),
                Yii::app()->createUrl('blog/post/view',array('id'=>$data['id'],'title'=>$data['title'])),
                array('target'=>'_blank')
            ); ?>
        </h2>
    </div>
    <div class="author">
        posted by <?php echo $data->author->username . ' on ' . date('F j, Y',$data->created); ?>
        <!-- Baidu Button BEGIN -->
        <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
            <a class="bds_qzone"></a>
            <a class="bds_tsina"></a>
            <a class="bds_tqq"></a>
            <a class="bds_renren"></a>
            <span class="bds_more">更多</span>
            <a class="shareCount"></a>
        </div>
        <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=138127" ></script>
        <script type="text/javascript" id="bdshell_js"></script>
        <script type="text/javascript">
            document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
        </script>
        <!-- Baidu Button END -->
        <div class="clear"></div>
    </div>
    <div class="content">
        <?php
        $this->beginWidget('CMarkdown', array('purifyOutput'=>true));
        echo $data->content;
        $this->endWidget();
        ?>
    </div>
    <div class="nav">
        <b>Tags:</b>
        <?php echo implode(', ', $data->tagLinks); ?>
        <br/>
        <?php echo CHtml::link('Permalink', $data->url); ?> |
        <?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); ?> |
        Last updated on <?php echo date('F j, Y',$data->updated); ?>
    </div>
</div>