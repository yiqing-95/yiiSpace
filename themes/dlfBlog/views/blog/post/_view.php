<li>
    <!-- 文章开始 --> 
    <div class="post-text">
        <div class="title">
          <a href="#" target="_blank" title="name" class="author"></a>
            <i class="line_h"></i>
            <h3><?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?></h3>
            <p>
              <?php echo $data->author->username; ?> /
              <?php if(isset($data->category->id) !=0): ?>
              <?php echo CHtml::link("{$data->category->name}",
                      array('post/index','category'=>$data->category->id,'alias'=>$data->category->alias),
                      array('title'=>$data->category->name,'target'=>'_blank')) 
              ?>/
              <?php endif;?>
                <span><?php echo date('F j, Y',$data->created) ?></span> / 
                 标签: <?php echo implode(', ', $data->tagLinks); ?>
            </p>
            <?php echo CHtml::link("{$data->commentCount}",$data->url.'#comments',array('class'=>'up','title'=>"{$data->commentCount}条评论")); ?> 
        </div>
        
       <!-- <div class="post-banner"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/1294987190_56.jpg" alt="post-banner" /></div> -->
       
        <div class="text">
            <?php
                $this->beginWidget('CMarkdown', array('purifyOutput'=>true));
                echo $data->content;
                $this->endWidget();
            ?> 
        </div>
        <div class="tools-bar">
            <ul>
              <!--<li class="browse">浏览:5,877</li> -->
              <li class="share"><!-- Baidu Button BEGIN -->
				    <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
				        <span class="bds_more">分享到：</span>
				        <a class="bds_qzone">QQ空间</a>
				        <a class="bds_tsina">新浪微博</a>
				        <a class="bds_tqq">腾讯微博</a>
				        <a class="bds_renren">人人网</a>
						<a class="shareCount"></a>
				    </div>
				<script type="text/javascript" id="bdshare_js" data="type=tools&amp;mini=1&amp;uid=138127" ></script>
				<script type="text/javascript" id="bdshell_js"></script>
				<script type="text/javascript">
					document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
				</script>
				<!-- Baidu Button END -->
			</li>
            </ul>
            <?php echo CHtml::link(CHtml::encode("阅读全文"), $data->url,array('class'=>'more','target'=>'_blank','title'=>'阅读全文')); ?>
            <div class="clear"></div>
        </div>
     </div>
     <!-- 文章结束 --> 
 </li>