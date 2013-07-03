THIS MODULE REQUIERES yii- BOOTSTRAP
And hide the index.php for url's.


Each portlet can be minimized, maximized, resized and moved,hidden or deleted.
Everything is saved in real time automaticly thrue ajax and is connected to the logged in user.
(Unless you check the default box, then the portlet gets user id 0 and is visible for all users).


Change your config (normally protected/config/main.php):
_ADD_  this line to your import array , 
	'import'=>array(
		'application.modules.sdashboard.components.*',
		'application.modules.sdashboard.models.*',
	),
And the module array :
'modules'=>array(
		'sdashboard'=>array(
		), 
	),


Then extract the sdashboard folder to to protected/modules ( so the structure becomes protected/modules/sdashboard)

You will then have to import the schema.sql file into your database to create the table needed for this dashboard.


Each portlet can have static content, but you can also define an url for each portlet.
The module will then make an ajax request to that url, and put the response into the portlet.
This url can be to any controller action.
Make sure you start the url with an '/' example: /post/ajaxView if you want to use the ajax function.
This is usefull if you want to use some serverside logic to determine what content to show.
Or a renderpartial or such.

In protected/modules/sdashboard/controllers/dashboardController.php you can set and customize access rules.


The layout of each portlet in admin mode is determined in protected/modules/sdashboard/views/dashboard/_portlet.php
The layout of each portlet in modify mode is determined in protected/modules/sdashboard/views/dashboard/_modifyportlets.php

And i made a viewwidget that only displays the portlets look is in protected/modules/sdashboard/views/dashboard/components/_viewportlets.php
You use this widget like this:
<?php $this->widget('viewPortlets',array('userid'=>0));?>


The css style is available in protected/modules/sdashboard/assets/css/sdashboard.css
And all javascript code  is in dashboard.js
There are three extra  plugins used for this:
bootbox, used to display confirm dialogs  the easy way.
toast, used to display notifications, you could remove this if you want to.
a customized markitup, the editor used. you can easily remove this to if you want.







