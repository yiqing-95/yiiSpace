<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div class="secondary-content">
		<div id="leftbar">
			<h2>Editor's picks</h2>
			<?php $this->widget('EditorsPicks', array(
				
			)); ?>
			<div class="left-ad">
				ads
			</div>
			<div class="left-ad">
			ads
			</div>
		</div><!-- content -->
	</div>
	<div class="main-content">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
		
	</div>
	<div class="promo-content">
		<div id="sidebar">
		
		<div class="search">
			<form action="http://www.dlf5.com/search" id="cse-search-box">
			  <div>
			    <input type="hidden" name="cx" value="partner-pub-2584272689913259:3000131576" />
			    <input type="hidden" name="ie" value="UTF-8" />
			    <input type="text" name="q" size="36" />
			    <input type="submit" name="sa" value="&#x641c;&#x7d22;" />
			  </div>
			</form>
			<script type="text/javascript" src="http://www.google.com.hk/coop/cse/brand?form=cse-search-box&amp;lang=zh-Hans"></script>
		</div>
		
		<h2>About this blog</h2>
		<div class="right-ad">
			ads
		</div>
			<?php $this->widget('UserMenu'); ?>

			<?php $this->widget('TagCloud', array(
				'maxTags'=>Yii::app()->params['tagCloudCount'],
			)); ?>

			<?php $this->widget('RecentComments', array(
				'maxComments'=>Yii::app()->params['recentCommentCount'],
			)); ?>
        
            <?php $this->widget('MonthlyArchives', array(
                'year'=>'年',
                'month'=>'月',
			)); ?>
		</div><!-- sidebar -->
	</div>
	<div class="clear"></div>
</div>
<?php $this->endContent(); ?>