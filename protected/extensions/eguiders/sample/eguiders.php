<?php
$this->breadcrumbs=array(
	'EGuiders',
);?>
<a href="index.php"><i class="icon-arrow-left"></i>&nbsp;back to homepage</a>

<?php
$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'intro',
		'next' 			=> 'position',
		'title'			=> 'Welcome',
		'buttons'		=> array(array('name'=>'Next')),
		'description' 	=> $this->renderPartial('_guide_intro',null,true),
		'overlay'		=> true,
		'xButton'		=> true,
		//'show'			=> true,
		'autoFocus'	    => true,
	)
);

$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'position',
		'next' 			=> 'sample',
		'title'			=> 'Positionning',
		'buttons' 		=> array(
			array('name'=>'Next'),
		),
		'description' 	=>  $this->renderPartial('_guide_position',null,true),
		'overlay'		=> false,
		'attachTo' 		=> '#firstAttach',
		'position' 		=> 6,
		'xButton'		=> true,
		'onShow'		=> 'js:function(){guiders.hideAll();}',
		'autoFocus'	    => true
	)
);


$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'sample',
		'title'			=> 'Sample',
		'next' 			=> 'api',
		'buttons' 		=> array(
			array('name'=>'Next'),
		),
		'description' 	=> $this->renderPartial('_guide_sample',null,true),
		'overlay'		=> false,
		'attachTo' 		=> '#sampleCode',
		'position' 		=> 11,
		'xButton'		=> true,
		'autoFocus'	    => true
	)
);

$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'api',
		'title'			=> 'Simple API',
		'next' 			=> 'scroll',
		'buttons' 		=> array(
			array('name'=>'Previous',	 'onclick'=> "js:function(){guiders.hideAll(); $('.highlight pre').hide(); guiders.show('sample');}"),
			array('name'=>'Show me more','onclick'=> "js:function(){guiders.next();}"),
			array('name'=>'Exit',		 'onclick'=> "js:function(){guiders.hideAll();}")
		),
		'description' 	=> $this->renderPartial('_guide_api',null,true),
		'overlay'		=> false,
		'attachTo' 		=> '#api',
		'position' 		=> 11,
		'xButton'		=> true,
		'onShow' 		=> 'js:function(){ $(".highlight pre").show();}',
		'autoFocus'	    => true,
		'closeOnEscape' => true,
	)
);
$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'scroll',
		'title'			=> "Focus on intresting stuff",
		'next'			=> 'style',
		'description' 	=> 'Use the <code>autoFocus</code> option to scroll to the target element so your guider is always visible.<br/>'.
			'With the <code>highlight</code> you can show your users what they must not miss in your page !! ',
		'attachTo' 		=> '#sampleCode2',
		'position'		=> 6,
		'highlight'		=> '#sampleCode2',
		'buttons'		=> array(array('name'=>'Next')),
		'autoFocus'	    => true,
		'overlay'       => true,
		'closeOnEscape' => true,
		'xButton'		=> true,
	)
);


$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'style',
		'title'			=> "Custom style is also available !",
		'next'			=> 'end',
		'description' 	=> "<b>Yes you can !</b> ... Simply register your own CSS style and choose a CSS class name "
			." that will be added to the parent enclosing DIV.",
		'classString' 	=> 'custom',
		'buttons' 		=> array(array('name'=>'Next')),
		'attachTo' 		=> '#sampleCode3',
		'position' 		=> 11,
		'cssFile'		=> Yii::app()->baseUrl. '/css/custom_guiders.css',
		'classString' 	=> 'custom',
		'autoFocus'	    => true,
	)
);

$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'end',
		'title'			=> "That's all folks !",
		'buttons' 		=> array(array('name'=>'Close')),
		'description' 	=> $this->renderPartial('_guide_end',null,true),
		'overlay'		=> true,
		'autoFocus'	    => true,
	)
);

// tour 2 : jump between pages
$nextLink = Yii::app()->createUrl('extension/eguiders2',array('#' => 'guider=page2_step2'));
$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'page1_step1',
		'title'			=> "Ready to leave the page ?",
		'buttons' 		=> array(
			array('name'=>'Let\'go',     'onclick'=> "js:function(){  document.location = '$nextLink';}"),
			array('name'=>'Exit',		 'onclick'=> "js:function(){guiders.hideAll();}")
		),
		'description' 	=> 'we are not going to travel very far from here, just jumpt to aother page and we are back.',
		'overlay'		=> true,
		'autoFocus'	    => true,
	)
);

$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'page1_step3',
		'title'			=> "... and back again",
		'buttons' 		=> array(array('name'=>'Close')),
		'description' 	=> 'End of the trip : please don\'t forget the guide',
		'overlay'		=> true,
		'autoFocus'	    => true,
		'isHashable'    => true
	)
);

?>
<div class="row">
	<div class="span5 offset2">
		<img src="images/eguiders.png" alt="jformValidate logo"/>
	</div>
	<div class="span5">
		<h2>
			<?php echo CHtml::link('Download', 'http://www.yiiframework.com/extension/eguiders/',array('title'=>'Download from Yii extension repository'));?>
			<div id="firstAttach">
				<a onclick="guiders.show('intro');return false;" href="#">Demo : Start The Guided tour</a>
			</div>
		</h2>
	</div>
</div>
<hr/>
<div class="row">
	<div class="span12">
	<blockquote>
  <p>EGuiders is a Yii extension that wraps an excellent JQuery plugin called <a href="https://github.com/jeff-optimizely/Guiders-JS" target="_guider">Guiders</a></p>
</blockquote>
		<h2>The Basic</h2>

		<p>Creating a Guider for your Yii webapp is easy : simply create a set of 'ext.guiders.EGuider' widgets chained together. Below is a basic example on how to create a guide. As you can see, the next button
		on this guide will drive the user to another Guide with id set to 'second'.</p>
		<div id="sampleCode">
			<?php
				$this->beginWidget('system.web.widgets.CTextHighlighter',array(
					'language'=>'PHP',
					'showLineNumbers'=>false,
				));
			?>
$this->widget('ext.guiders.EGuider', array(
		'id'          => 'first',
		'next'        => 'second',
		'title'       => 'Guider title',
		'buttons'     => array(array('name'=>'Next')),
		'description' => '<b>here you should put some intresting text</b>',
		'overlay'     => true,
		'xButton'     => true,
		// look here !! 'show' is true, so that means this guider will be
		// automatically displayed when the page loads
		'show'        => true,
		'autoFocus'	  => true
	)
);
			<?php $this->endWidget();?>
		</div>
		<p><span class="label label-warning">Upgrade from 1.0</span> the <code>autoFocus</code> option is required to garantee that the guider will scroll to the element. Previously
		this option was not needed as the scroll was performed by default.</p>
		<p>The Guider plugin provides a lot of options that allows you to customize almost every aspect of the guider tour you want to offer to your users.</p>
		
		
		<div id="api" class="highlight" >
			<p>From javascript, you can call some API to interact with your guides.</p>
			<pre style="display: none">
				<span class="nx">guiders</span><span class="p">.</span><span class="nx">hideAll</span><span class="p">();</span> <span class="c1">// hides all guiders</span>
				<span class="nx">guiders</span><span class="p">.</span><span class="nx">next</span><span class="p">();</span> <span class="c1">// hides the last shown guider, if shown, and advances to the next guider</span>
				<span class="nx">guiders</span><span class="p">.</span><span class="nx">show</span><span class="p">(</span><span class="nx">id</span><span class="p">);</span> <span class="c1">// shows the guider, given the id used at creation</span>
			</pre>
		</div>

		<h2>Customize Buttons</h2>

		<p>
			You can define your own handler for buttons included in the Guide. This can bring some interaction with the user : you don't
			have to Guide a passive user ! let's make him work a little !. The following widget redefine buttons displayed by the guider.
		</p>
		
		<div id="sampleCode2">
			<?php
				$this->beginWidget('system.web.widgets.CTextHighlighter',array(
					'language'=>'PHP',
					'showLineNumbers'=>false,
				));
			?>
$this->widget('ext.eguiders.EGuider', array(
		'id'           => 'second',
		'title'        => 'Simple API',
		'next'         => 'third',
		'buttons'      => array(
		    array(
			    'name'   => 'Previous',
			    'onclick'=> "js:function(){guiders.hideAll(); $('.highlight pre').hide(); guiders.show('sample');}"
			),
			array(
				'name'   => 'Show me more',
				'onclick'=> "js:function(){guiders.next();}"
			),
			array(
				'name'   => 'Exit',
				'onclick'=> "js:function(){guiders.hideAll();}"
			)
		),
		// why not call renderPartial to get the content of the Guide ? .. yeah, why not ?
		'description'   => $this->renderPartial('_guide_api',null,true),
		'overlay'       => false,
		// you can attach your guide to any element in the page thanks to JQuery selectors
		'attachTo'      => '#elementId',
		'position'      => 11,
		'xButton'       => true,
		'onShow'        => 'js:function(){ $(".highlight pre").show();}',
		'closeOnEscape' => true,
	)
);
			<?php $this->endWidget();?>
		</div>
		<p><span class="label label-info">New feature</span> since version 1.1, thanks to the <code>closeOnEscape</code> option it is now possible to configure a guider to it closes when user hits the <em>Escape</em> key.</p>

		<h2>Guide between pages</h2>
		<p>
			It is possible to guide your user among one or more pages by using hash url format. To achieve this, you must add the '#guide=guide_name' parameter to the
			url of the target page. Replace 'guide_name' by the id of the guide that will be displayed on the target page and that's it !
		</p>
		<p>
			<a onclick="guiders.show('page1_step1');return false;" href="#">Jump in The Guided tour</a>
		</p>
		<div id="sampleCode2">
			<?php
				$this->beginWidget('system.web.widgets.CTextHighlighter',array(
					'language'=>'PHP',
					'showLineNumbers'=>false,
				));
			?>
$nextLink = Yii::app()->createUrl('extension/eguiders2',array('#' => 'guider=page2_step2'));
$this->widget('ext.eguiders.EGuider', array(
		'id'            => 'page1_step1',
		'title'         => "Ready to leave the page ?",
		'buttons'       => array(
			array('name'=>'Let\'go', 'onclick'=> "js:function(){  document.location = '$nextLink';}"),
			array('name'=>'Exit',    'onclick'=> "js:function(){guiders.hideAll();}")
		),
		'description'   => 'we are not going to travel very far from here, just jumpt to aother page and we are back.',
		'overlay'       => true,
		'autoFocus'     => true,
	)
);
			<?php $this->endWidget();?>
		</div>
				
		<h2>Use your own style</h2>

		<p>
			By setting both the 'classString' and 'cssFile' options, you can customize the style of your guider.</br>
			It is important to understand that in all Guiders included into a page, share the same CSS files, so if you
			delcare a stylesheet on the first guide of your page, no need to declare it for other guides in the same page.<br/>
			Another point is that even if you declare a custom stylesheet, <b>the default CSS file provided with the Guiders JQuery
			plugin is always included</b> : your custom stylesheet must overloads it and redefine styles you want to change.
		</p>

		<div id="sampleCode3">
			<?php
				$this->beginWidget('system.web.widgets.CTextHighlighter',array(
					'language'=>'PHP',
					'showLineNumbers'=>false,
				));
			?>
$this->widget('ext.eguiders.EGuider', array(
		'id'          => 'style',
		'title'       => "Custom style is also available !",
		'next'        => 'end',
		'description' => "<b>Yes you can !</b> ... Simply register your own CSS style and choose a CSS class name "
		." that will be added to the parent enclosing DIV.",
		'classString' => 'custom',
		'buttons'     => array(array('name'=>'Next')),
		
		// remember that this CSS is going to overloads the default one,
		// not replace it !!
		
		'cssFile'     => Yii::app()->baseUrl. '/css/custom_guiders.css',
		'classString' => 'custom',
	)
);
			<?php $this->endWidget();?>
			<p>There's a lot more on using the <b>Guiders</b> plugin, so have a look at the official <a href="https://github.com/jeff-optimizely/Guiders-JS">Project page</a></p>
		</div>
		
</div>