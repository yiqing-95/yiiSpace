<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-10
 * Time: 上午1:30
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
Yii::import('zii.widgets.CDetailView');

class CascadeDetailView extends CDetailView {
    /**
     * @var string the name of the tag for rendering the detail view. Defaults to 'table'.
     * If set to null, no tag will be rendered.
     * @see itemTemplate
     */
    public $tagName='dl';
    /**
     * @var string the template used to render a single attribute. Defaults to a table row.
     * These tokens are recognized: "{class}", "{label}" and "{value}". They will be replaced
     * with the CSS class name for the item, the label and the attribute value, respectively.
     * @see itemCssClass
     */
    public $itemTemplate="<dt>{label}</dt><dd>{value}</dd>\n";
    /**
     * @var array the CSS class names for the items displaying attribute values. If multiple CSS class names are given,
     * they will be assigned to the items sequentially and repeatedly.
     * Defaults to <code>array('odd', 'even')</code>.
     */
    public $itemCssClass=array('odd','even');
    /**
     * @var array the HTML options used for {@link tagName}
     */
    public $htmlOptions=array('class'=>'detail-view');
}