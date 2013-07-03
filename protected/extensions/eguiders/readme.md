![EGuiders](http://s172418307.onlinehome.fr/project/yiiDemo/images/eguiders.png "EGuiders")

Would you like to focus user attention on this new feature you've implemented last night ? Do you need to need to introduce a brand new GUI to your users ? and what about a simple and interactive tutorial that can be easely added to your pages ? ... well, one solution is to use the Guiders JQuery plugin, now available in Yii as the EGuiders extension.

More than words, check the [demo page](http://s172418307.onlinehome.fr/project/yiiDemo/index.php?r=extension/eguiders "Demo page").

The **EGuiders** is a wrapper for the [Guiders JQuery Plugin](https://github.com/jeff-optimizely/Guiders-JS "Guiders JQuery plugin"). 

##Requirements

Yii 1.1 or above

##Usage

To use this extension, simply declare a widget to create a guide.

~~~
[php]
$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'intro',
		'next' 			=> 'position',
		'title'			=> 'Welcome',
		'buttons'		=> array(array('name'=>'Next')),
		'description' 	=> $this->renderPartial('_guide_intro',null,true),
		'overlay'		=> true,
		'xButton'		=> true,
		'show'			=> true
	)
);
~~~

You can of course create many guiders for one page, chain them and create a complete step by step route to guide your user through your page.

Additional features :

- **custom CSS style** : overloads default style and turn your guide into a _beautiful guide_
- **position and orientation** : attach a guide to any HTML element with JQuery selector
- **dynamics** : add your own buttons to a guide and define js handler
- **jump** : a guided tour can also go from page to another (see demo)
- **highlight** : display overlay except for the highlighted element

With some imaginaton, a boring tour can become a great interactive user experience ! I hope it will be useful to someone...

##Resources
_note that the EGuiders zip file to download includes the demo sample code._

 * [Try out a demo](http://s172418307.onlinehome.fr/project/yiiDemo/index.php?r=extension/eguiders "Demo Page")
 * [Guiders JQuery plugin project page](https://github.com/jeff-optimizely/Guiders-JS "Guiders JQuery plugin project page")
 * [Forum discussion](http://www.yiiframework.com/forum/index.php?/topic/25077-extension-eguiders/#entry121159 "Forum discussion")
 
Please report any issue in the Forum discussion thread and not as a comment in this page.

##Changes
###version 1.1.0.0
* updated to Guider 1.2.8 new plugin options (autoFocus, closeOnEscape, isHashable, onClose, onHide)
* demo pages updated

###version 1.0.0.0
* initial release
