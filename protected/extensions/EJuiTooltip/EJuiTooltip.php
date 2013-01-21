<?php

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * EJuiTooltip class file.
 *
 * EJuiTooltip displays a tooltip widget.
 *
 * EJuiTooltip encapsulates the {@link http://jqueryui.com/demos/tooltip/ JUI Tooltip}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('application.extensions.juitooltip.EJuiTooltip',array(
 *     // elements selector, by default their title attribute will be used as content
 *     'selector'=>'.tooltip',
 *     // additional javascript options for the tooltip plugin
 *     'options'=>array(
 *         'scope'=>'myScope',
 *     ),
 * ));
 *
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI Tooltip plugin. Please refer to
 * the {@link http://jqueryui.com/demos/tooltip/ JUI Tooltip} documentation
 * for possible options (name-value pairs).
 *
 *
 * @author Dimitar Dinchev <dinchev.dimitar@gmail.com>
 * @link http://www.yiiframework.com/
*/
class EJuiTooltip extends CJuiWidget
{
	/**
	 * @var tooltip selector, defaults to document (all elements with title attribute
	 * will have tooltip with the title attribute content used as tooltip content)
	 */
	public $selector = 'document';

	/**
	 * Renders the close tag of the tooltip element.
	 */
	public function run()
	{
		$options = CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__ . $this->selector, "jQuery('{$this->selector}').tooltip($options);");
	}
}