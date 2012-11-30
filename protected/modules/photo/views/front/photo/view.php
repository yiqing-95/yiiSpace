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

<h1>View Photo #<?php echo $model->id; ?></h1>
<?php
$this->widget('photo.extensions.galleriffic.JGalleriffic', array(
    'debug' => true
));
cs()->registerCssFile(JGalleriffic::getAssetsUrl() . '/css/basic.css');
cs()->registerCssFile(JGalleriffic::getAssetsUrl() . '/css/galleriffic-yiispace.css');
?>

<!-- We only want the thunbnails to display when javascript is disabled -->
<script type="text/javascript">
    document.write('<style>.noscript { display: none; }</style>');
</script>
<div class="fluid-row">

    <div class="span8">
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

    <div class="span3">
        <div class="navigation-container">
            <div id="thumbs" class="navigation">
                <div class="span2">
                    <a class="pageLink prev" href="#" title="Previous Page">上页 &nbsp;</a>
                    <a class="pageLink next" href="#" title="next Page">下页</a>
                </div>


                <ul class="thumbs noscript">
<!--                    开始同相册的迭代-->
                    <?php  $i = $idxInAlbum = 1 ; ?>
                    <?php foreach($photos as $photo): ?>
                    <?php  if($photo->id == $model->id){
                            $idxInAlbum = $i;
                    }else{
                        $i++ ;
                    } ;?>
                    <li>
                        <a class="thumb" name="leaf"
                           href="<?php echo $photo->getViewUrl(); ?>" title="Title #0">
                            <img src="<?php echo $photo->getThumbUrl(); ?>" alt="Title #0"/>
                        </a>

                        <div class="caption">
                            <div class="image-title"><?php echo  $photo->title; ?></div>
                            <div class="image-desc">views<?php echo $photo->views ; ?></div>
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

</div>
</div>
</div>
<!-- Start Advanced Gallery Html Containers -->




<!-- End Gallery Html Containers -->
<div style="clear: both;"></div>
<?php echo $this->layout = '//layouts/column1'; ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        // We only want these styles applied when javascript is enabled
        $('div.content').css('display', 'block');

        // Initially set opacity on thumbs and add
        // additional styling for hover effect on thumbs
        var onMouseOutOpacity = 0.67;
        $('#thumbs ul.thumbs li, div.navigation a.pageLink').opacityrollover({
            mouseOutOpacity:onMouseOutOpacity,
            mouseOverOpacity:1.0,
            fadeSpeed:'fast',
            exemptionSelector:'.selected'
        });

        // Initialize Advanced Galleriffic Gallery
        var gallery = $('#thumbs').galleriffic({
            delay:2500,
            numThumbs:10,
            preloadAhead:10,
            enableTopPager:false,
            enableBottomPager:false,
            imageContainerSel:'#slideshow',
            controlsContainerSel:'#controls',
            captionContainerSel:'#caption',
            loadingContainerSel:'#loading',
            renderSSControls:true,
            renderNavControls:true,
            playLinkText:'自动播放',
            pauseLinkText:'暂停',
            prevLinkText:'上张',
            nextLinkText:'下张',
            nextPageLinkText:'下页',
            prevPageLinkText:'上页',
            enableHistory:true,
            autoStart:false,
            syncTransitions:true,
            defaultTransitionDuration:900,
            onSlideChange:function (prevIndex, nextIndex) {
                // 'this' refers to the gallery, which is an extension of $('#thumbs')
                this.find('ul.thumbs').children()
                    .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
                    .eq(nextIndex).fadeTo('fast', 1.0);

                // Update the photo index display
                this.$captionContainer.find('div.photo-index')
                    .html('Photo ' + (nextIndex + 1) + ' of ' + this.data.length);
            },
            onPageTransitionOut:function (callback) {
                this.fadeTo('fast', 0.0, callback);
            },
            onPageTransitionIn:function () {
                var prevPageLink = this.find('a.prev').css('visibility', 'hidden');
                var nextPageLink = this.find('a.next').css('visibility', 'hidden');

                // Show appropriate next / prev page links
                if (this.displayedPage > 0)
                    prevPageLink.css('visibility', 'visible');

                var lastPage = this.getNumPages() - 1;
                if (this.displayedPage < lastPage)
                    nextPageLink.css('visibility', 'visible');

                this.fadeTo('fast', 1.0);
            }
        });


        /**************** Event handlers for custom next / prev page links **********************/

        gallery.find('a.prev').click(function (e) {
            gallery.previousPage();
            e.preventDefault();
        });

        gallery.find('a.next').click(function (e) {
            gallery.nextPage();
            e.preventDefault();
        });

        /****************************************************************************************/

        /**** Functions to support integration of galleriffic with the jquery.history plugin ****/

            // PageLoad function
            // This function is called when:
            // 1. after calling $.historyInit();
            // 2. after calling $.historyLoad();
            // 3. after pushing "Go Back" button of a browser
        function pageload(hash) {
            // alert("pageload: " + hash);
            // hash doesn't contain the first # character.
            if (hash) {
                $.galleriffic.gotoImage(hash);
            } else {
                gallery.gotoIndex(0);
            }
        }

        // Initialize history plugin.
        // The callback is called at once by present location.hash.
        $.historyInit(pageload, "advanced.html");

        // set onlick event for buttons using the jQuery 1.3 live method
        $("a[rel='history']").live('click', function (e) {
            if (e.button != 0) return true;

            var hash = this.href;
            hash = hash.replace(/^.*#/, '');

            // moves to a new page.
            // pageload is called at once.
            // hash don't contain "#", "?"
            $.historyLoad(hash);

            return false;
        });

        /**
         * @see http://stackoverflow.com/questions/9253036/set-first-selected-image-in-galleriffic-jquery-plugin
         * 加载当前相片
         */
        $("img","a[href='#<?php echo $idxInAlbum ;?>']").trigger('click');

    });
    /****************************************************************************************/

</script>
