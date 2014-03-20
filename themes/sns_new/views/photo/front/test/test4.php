 <?php
    $this->widget('photo.extensions.exposure.JExposure',array(

    ));
   //cs()->registerCssFile(JExposure::getAssetsUrl().'/demos/demo2.css');
    ?>

 <script type="text/javascript">
     $(function(){
         var gallery = $('#images');
         gallery.exposure({controlsTarget : '#controls',
             controls : { prevNext : true, pageNumbers : true, firstLast : false },
             visiblePages : 2,
             slideshowControlsTarget : '#slideshow',
             onThumb : function(thumb) {
                 var li = thumb.parents('li');
                 var fadeTo = li.hasClass($.exposure.activeThumbClass) ? 1 : 0.3;

                 thumb.css({display : 'none', opacity : fadeTo}).stop().fadeIn(200);

                 thumb.hover(function() {
                     thumb.fadeTo('fast',1);
                 }, function() {
                     li.not('.' + $.exposure.activeThumbClass).children('img').fadeTo('fast', 0.3);
                 });
             },
             onImage : function(image, imageData, thumb) {
                 // Fade out the previous image.
                 image.siblings('.' + $.exposure.lastImageClass).stop().fadeOut(500, function() {
                     $(this).remove();
                 });

                 // Fade in the current image.
                 image.hide().stop().fadeIn(1000);

                 // Fade in selected thumbnail (and fade out others).
                 if (gallery.showThumbs && thumb && thumb.length) {
                     thumb.parents('li').siblings().children('img.' + $.exposure.selectedImageClass).stop().fadeTo(200, 0.3, function() { $(this).removeClass($.exposure.selectedImageClass); });
                     thumb.fadeTo('fast', 1).addClass($.exposure.selectedImageClass);
                 }
             },
             onPageChanged : function() {
                 // Fade in thumbnails on current page.
                 gallery.find('li.' + $.exposure.currentThumbClass).hide().stop().fadeIn('fast');
             }
         });
     });
 </script>
<div class="fluid-row" id="main">

    <div class="span4">
        <div class="panel">
            <ul id="images">
                <?php for($i=0 ; $i<10; $i++): ?>
                <li><a href="<?php echo JExposure::getAssetsUrl(); ?>/demos/slides/IMG_2198.jpg"><img src="<?php echo JExposure::getAssetsUrl(); ?>/demos/thumbs/IMG_2198.jpg" title="Home made" /></a></li>
                <?php endfor; ?>
            </ul>
            <div id="controls"></div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="span8">

        <div id="exposure" ></div>
        <div class="clear"></div>
        <div id="slideshow"></div>
    </div>
</div>

