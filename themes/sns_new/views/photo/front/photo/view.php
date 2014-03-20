<?php
$this->breadcrumbs = array(
    'Photos' => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => 'List Photo', 'url' => array('index')),
    array('label' => 'Create Photo', 'url' => array('create')),
    array('label' => 'Update Photo', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Photo', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Photo', 'url' => array('admin')),
);
?>
<?php
   $this->widget('widgets.YsGleanWidget',array(
       'objectType'=>'photo',
       'objectId'=>$model->primaryKey,
       'actionConfirmation'=>'确定该图片？',
   ));
?>
<h1>View Photo #<?php echo $model->id; ?>
    <?php
    $this->widget('application.components.sysVoting.YsStarRating', array(
        'name' => 'ratingAjaxDynamicTarget',
        'objectName' => 'photo',
        'objectId' => new CJavaScriptExpression('$(".caption",$(".image-caption.current")).attr("photo_id")'),
    ));
    ?>
    <?php
    $this->widget('voting.widgets.ThumbVotingWidget', array(
        'mode' => 'updown1',
        'objectName' => 'photo',
        'objectId' => $model->id,
        'upValue' => $model->up_votes,
        'downValue' => $model->down_votes,
    ));
    ?>
</h1>
<?php
/*
$this->widget('photo.extensions.galleriffic.JGalleriffic', array(
    'debug' => true
));
cs()->registerCssFile(JGalleriffic::getAssetsUrl() . '/css/basic.css');
cs()->registerCssFile(JGalleriffic::getAssetsUrl() . '/css/galleriffic-yiispace.css');
*/
?>

<!-- We only want the thunbnails to display when javascript is disabled -->
<script type="text/javascript">
    document.write('<style>.noscript { display: none; }</style>');
</script>
<div class="fluid-row">

    <div class="span9 img-viewer">
        <div class="content">
            <div class="slideshow-container">
                <div id="controls" class="controls"></div>
                <div id="loading" class="loader"></div>
                <div id="slideshow" class="slideshow"></div>
            </div>
            <div id="caption" class="caption-container">
                <div class="photo-index"></div>
            </div>

        </div>

    </div>

    <div class="span2 ">
        <div class="navigation-container">
            <div id="thumbs" class="navigation">
                <div class="span2">
                    <a class="pageLink prev" href="#" title="Previous Page">上页 &nbsp;</a>
                    <a class="pageLink next" href="#" title="next Page">下页</a>
                </div>


                <ul class="thumbs noscript">
                    <!--                    开始同相册的迭代-->
                    <?php  $i = $idxInAlbum = 1; ?>
                    <?php foreach ($photos as $photo): ?>
                    <?php  if ($photo->id == $model->id) {
                        $idxInAlbum = $i;
                    } else {
                        $i++;
                    }
                    ;?>
                    <li>
                        <a class="thumb" name="leaf"
                           href="<?php echo $photo->getViewUrl(); ?>" title="Title #0">
                            <img src="<?php echo $photo->getThumbUrl(); ?>" alt="Title #0"/>
                        </a>

                        <div class="caption" photo_id="<?php echo  $photo->id; ?>">
                            <div class="image-title"><?php echo  $photo->title; ?></div>
                            <div class="image-desc">views<?php echo $photo->views; ?></div>
                            <div class="image-rating">
                                得票：
                                <span class="rate badge badge-info"><?php echo $photo->rate; ?></span>
                                |投票人数：<span class="rate-count badge badge-info"><?php echo $photo->rate_count; ?></span>
                            </div>
                            <div class="download">
                                <a href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_b.jpg">Download
                                    Original</a>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                    <!--                    结束同相册的迭代-->
                </ul>
            </div>
        </div>

    </div>
    <!--            here is  comment list and comment form  start -->
    <div class="span9" id="commentContainer">
        <?php $this->renderPartial('_commentsAndForm',array('model'=>$model)); ?>
    </div>
    <!--            here is  comment list and comment form end -->
</div>

<!-- Start Advanced Gallery Html Containers -->




<!-- End Gallery Html Containers -->
<div style="clear: both;"></div>

<script type="text/javascript">


    /****************************************************************************************/

    /**
     * @see http://stackoverflow.com/questions/7392058/more-efficient-way-to-handle-window-scroll-functions-in-jquery
     * @param waitTime
     * @param fn
     * can be used for fixing some element showing   when scroll the window
     */
    $.fn.scrolled = function (waitTime, fn) {
        var tag = "scrollTimer";
        this.scroll(function () {
            var self = $(this);
            var timer = self.data(tag);
            if (timer) {
                clearTimeout(timer);
            }
            timer = setTimeout(function () {
                self.data(tag, null);
                fn();
            }, waitTime);
            self.data(tag, timer);
        });
    }

    /**
     *
     * @type {Boolean}
     */
    var ajaxLoadingFlag = false;
    $(window).scrolled(500, function () {
        var headerBottom = $(".img-viewer").height();

        var ScrollTop = $(window).scrollTop();


        var loadPhotoComments = function(photoId){
            // loading .....
            if(ajaxLoadingFlag == true ) {
                return ;
            }
            var url = "<?php echo  $this->createUrl('/sys/comment',array('objectName'=>'photo','objectId'=>'{objectId}'));?>";
           // alert(url);
            url = url.replace(encodeURIComponent('{objectId}'),photoId);
            ajaxLoadingFlag = true ;
            $("#commentContainer").load(url,function(){
                ajaxLoadingFlag = false;
            });
        };

        if (ScrollTop > headerBottom) {
            // check if the current image id is equal to the comment objectid ;
            //alert('yeeee!');
            var cmtObjectId = $(".comment-widget").attr("object_id");
            var imgId = $(".caption",$(".image-caption.current")).attr("photo_id");
            if(cmtObjectId === imgId){
                //alert("yes it is!");
            }else{
               // alert('not');
                loadPhotoComments(imgId);
            }
        } else {
            //alert("ok!");
        }

    });

</script>
