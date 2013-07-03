 <?php
    $this->widget('photo.extensions.galleriffic.JGalleriffic',array(
      'debug'=>true
    ));
 cs()->registerCssFile(JGalleriffic::getAssetsUrl().'/css/basic.css');
    cs()->registerCssFile(JGalleriffic::getAssetsUrl().'/css/galleriffic-yiispace.css');
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
        <a class="pageLink prev"  href="#" title="Previous Page">上页 &nbsp;</a>
        <a class="pageLink next"  href="#" title="next Page">下页</a>
    </div>


    <ul class="thumbs noscript">
    <li>
        <a class="thumb" name="leaf" href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015.jpg" title="Title #0">
            <img src="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_s.jpg" alt="Title #0" />
        </a>
        <div class="caption">
            <div class="image-title">Title #0</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" name="drop" href="http://farm3.static.flickr.com/2404/2538171134_2f77bc00d9.jpg" title="Title #1">
            <img src="http://farm3.static.flickr.com/2404/2538171134_2f77bc00d9_s.jpg" alt="Title #1" />
        </a>
        <div class="caption">
            Any html can be placed here ...
        </div>
    </li>

    <li>
        <a class="thumb" name="bigleaf" href="http://farm3.static.flickr.com/2093/2538168854_f75e408156.jpg" title="Title #2">
            <img src="http://farm3.static.flickr.com/2093/2538168854_f75e408156_s.jpg" alt="Title #2" />
        </a>
        <div class="caption">
            <div class="image-title">Title #2</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2093/2538168854_f75e408156_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" name="lizard" href="http://farm4.static.flickr.com/3153/2538167690_c812461b7b.jpg" title="Title #3">
            <img src="http://farm4.static.flickr.com/3153/2538167690_c812461b7b_s.jpg" alt="Title #3" />
        </a>
        <div class="caption">
            <div class="image-title">Title #3</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm4.static.flickr.com/3153/2538167690_c812461b7b_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm4.static.flickr.com/3150/2538167224_0a6075dd18.jpg" title="Title #4">
            <img src="http://farm4.static.flickr.com/3150/2538167224_0a6075dd18_s.jpg" alt="Title #4" />
        </a>
        <div class="caption">
            <div class="image-title">Title #4</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm4.static.flickr.com/3150/2538167224_0a6075dd18_b.jpg">Download Original</a>
            </div>
        </div>
    </li>


    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2139/2083503622_5b17f16a60.jpg" title="Title #13">
            <img src="http://farm3.static.flickr.com/2139/2083503622_5b17f16a60_s.jpg" alt="Title #13" />
        </a>
        <div class="caption">
            <div class="image-title">Title #13</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2139/2083503622_5b17f16a60_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2041/2083498578_114e117aab.jpg" title="Title #14">
            <img src="http://farm3.static.flickr.com/2041/2083498578_114e117aab_s.jpg" alt="Title #14" />
        </a>
        <div class="caption">
            <div class="image-title">Title #14</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2041/2083498578_114e117aab_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2149/2082705341_afcdda0663.jpg" title="Title #15">
            <img src="http://farm3.static.flickr.com/2149/2082705341_afcdda0663_s.jpg" alt="Title #15" />
        </a>
        <div class="caption">
            <div class="image-title">Title #15</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2149/2082705341_afcdda0663_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2014/2083478274_26775114dc.jpg" title="Title #16">
            <img src="http://farm3.static.flickr.com/2014/2083478274_26775114dc_s.jpg" alt="Title #16" />
        </a>
        <div class="caption">
            <div class="image-title">Title #16</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2014/2083478274_26775114dc_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2194/2083464534_122e849241.jpg" title="Title #17">
            <img src="http://farm3.static.flickr.com/2194/2083464534_122e849241_s.jpg" alt="Title #17" />
        </a>
        <div class="caption">
            <div class="image-title">Title #17</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2194/2083464534_122e849241_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm4.static.flickr.com/3127/2538173236_b704e7622e.jpg" title="Title #18">
            <img src="http://farm4.static.flickr.com/3127/2538173236_b704e7622e_s.jpg" alt="Title #18" />
        </a>
        <div class="caption">
            <div class="image-title">Title #18</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm4.static.flickr.com/3127/2538173236_b704e7622e_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2375/2538172432_3343a47341.jpg" title="Title #19">
            <img src="http://farm3.static.flickr.com/2375/2538172432_3343a47341_s.jpg" alt="Title #19" />
        </a>
        <div class="caption">
            <div class="image-title">Title #19</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2375/2538172432_3343a47341_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2353/2083476642_d00372b96f.jpg" title="Title #20">
            <img src="http://farm3.static.flickr.com/2353/2083476642_d00372b96f_s.jpg" alt="Title #20" />
        </a>
        <div class="caption">
            <div class="image-title">Title #20</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2353/2083476642_d00372b96f_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm3.static.flickr.com/2201/1502907190_7b4a2a0e34.jpg" title="Title #21">
            <img src="http://farm3.static.flickr.com/2201/1502907190_7b4a2a0e34_s.jpg" alt="Title #21" />
        </a>
        <div class="caption">
            <div class="image-title">Title #21</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm3.static.flickr.com/2201/1502907190_7b4a2a0e34_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm2.static.flickr.com/1116/1380178473_fc640e097a.jpg" title="Title #22">
            <img src="http://farm2.static.flickr.com/1116/1380178473_fc640e097a_s.jpg" alt="Title #22" />
        </a>
        <div class="caption">
            <div class="image-title">Title #22</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm2.static.flickr.com/1116/1380178473_fc640e097a_b.jpg">Download Original</a>
            </div>
        </div>
    </li>

    <li>
        <a class="thumb" href="http://farm2.static.flickr.com/1260/930424599_e75865c0d6.jpg" title="Title #23">
            <img src="http://farm2.static.flickr.com/1260/930424599_e75865c0d6_s.jpg" alt="Title #23" />
        </a>
        <div class="caption">
            <div class="image-title">Title #23</div>
            <div class="image-desc">Description</div>
            <div class="download">
                <a href="http://farm2.static.flickr.com/1260/930424599_e75865c0d6_b.jpg">Download Original</a>
            </div>
        </div>
    </li>
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
<?php echo $this->layout = '//layouts/column1' ;?>
 <script type="text/javascript">
     jQuery(document).ready(function($) {
         // We only want these styles applied when javascript is enabled
         $('div.content').css('display', 'block');

         // Initially set opacity on thumbs and add
         // additional styling for hover effect on thumbs
         var onMouseOutOpacity = 0.67;
         $('#thumbs ul.thumbs li, div.navigation a.pageLink').opacityrollover({
             mouseOutOpacity:   onMouseOutOpacity,
             mouseOverOpacity:  1.0,
             fadeSpeed:         'fast',
             exemptionSelector: '.selected'
         });

         // Initialize Advanced Galleriffic Gallery
         var gallery = $('#thumbs').galleriffic({
             delay:                     2500,
             numThumbs:                 10,
             preloadAhead:              10,
             enableTopPager:            false,
             enableBottomPager:         false,
             imageContainerSel:         '#slideshow',
             controlsContainerSel:      '#controls',
             captionContainerSel:       '#caption',
             loadingContainerSel:       '#loading',
             renderSSControls:          true,
             renderNavControls:         true,
             playLinkText:              '自动播放',
             pauseLinkText:             '暂停',
             prevLinkText:              '上张',
             nextLinkText:              '下张',
             nextPageLinkText:          '下页',
             prevPageLinkText:          '上页',
             enableHistory:             true,
             autoStart:                 false,
             syncTransitions:           true,
             defaultTransitionDuration: 900,
             onSlideChange:             function(prevIndex, nextIndex) {
                 // 'this' refers to the gallery, which is an extension of $('#thumbs')
                 this.find('ul.thumbs').children()
                     .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
                     .eq(nextIndex).fadeTo('fast', 1.0);

                 // Update the photo index display
                 this.$captionContainer.find('div.photo-index')
                     .html('Photo '+ (nextIndex+1) +' of '+ this.data.length);
             },
             onPageTransitionOut:       function(callback) {
                 this.fadeTo('fast', 0.0, callback);
             },
             onPageTransitionIn:        function() {
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

         gallery.find('a.prev').click(function(e) {
             gallery.previousPage();
             e.preventDefault();
         });

         gallery.find('a.next').click(function(e) {
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
             if(hash) {
                 $.galleriffic.gotoImage(hash);
             } else {
                 gallery.gotoIndex(0);
             }
         }

         // Initialize history plugin.
         // The callback is called at once by present location.hash.
         $.historyInit(pageload, "advanced.html");

         // set onlick event for buttons using the jQuery 1.3 live method
         $("a[rel='history']").live('click', function() {
             if (e.button != 0) return true;

             var hash = this.href;
             hash = hash.replace(/^.*#/, '');

             // moves to a new page.
             // pageload is called at once.
             // hash don't contain "#", "?"
             $.historyLoad(hash);

             return false;
         });

         /****************************************************************************************/
     });
 </script>
