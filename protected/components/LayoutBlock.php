<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-28
 * Time: 下午5:35
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------
 * @see http://www.yiiframework.com/wiki/127/dynamic-sidebar-using-cclipwidget/
 *
 * -----------------------------------------------------------------------------
 */
class LayoutBlock extends CComponent
{
    /**
     * @var string
     */
    private $_id ;

    /**
     * @var string
     */
    private $_content;

    /**
     * @var int
     */
    private $_weight = 0;

    /**
     * @var bool|string
     */
    private $_visible = true;

    /**
     * @var array
     */
    private $_htmlOptions = array();

    /**
     * @var string
     */
    private $_tagName ;

    /**
     * @var array
     */
    private $_defaultHtmlOptions = array(
        'class' => 'block',
    );

    const DEFAULT_BLOCK_TAG = 'div';

    /**
     * @param $id
     * @param $content
     * @param int $weight
     * @param bool $visible
     * @param array $htmlOptions
     * @param string $tagName
     */
    public function __construct($id, $content, $weight = 0, $visible = true, $htmlOptions = array(), $tagName = self::DEFAULT_BLOCK_TAG)
    {
        $this->setId($id);
        $this->setContent($content);
        $this->setWeight($weight);
        $this->setVisible($visible);
        $this->setHtmlOptions($htmlOptions);
        $this->setTagName($tagName);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->_weight;
    }

    /**
     * @return bool|string
     */
    public function getVisible()
    {
        return $this->_visible;
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->_tagName;
    }

    /**
     * @param $id
     * @return LayoutBlock
     * @throws CException
     */
    public function setId($id)
    {
        if (!is_string($id)) {
            throw new CException(Yii::t('application', 'The block id must be a string.'));
        }
        $this->_id = $id;
        return $this;
    }

    /**
     * @param $content
     * @return LayoutBlock
     * @throws CException
     */
    public function setContent($content)
    {
        if (!is_string($content)) {
            throw new CException(Yii::t('application', 'The block content must be a string.'));
        }
        $this->_content = $content;
        return $this;
    }

    /**
     * @param $weight
     * @return LayoutBlock
     * @throws CException
     */
    public function setWeight($weight)
    {
        if (!is_numeric($weight)) {
            throw new CException(Yii::t('application', 'The block weight must be a number.'));
        }
        $this->_weight = (int)$weight;
        return $this;
    }

    /**
     * @param $htmlOptions
     * @param bool $mergeOld
     * @return LayoutBlock
     * @throws CException
     */
    public function setHtmlOptions($htmlOptions, $mergeOld = false)
    {
        if (!is_array($htmlOptions)) {
            throw new CException(Yii::t('application', 'The block html options must be a number.'));
        }
        if ($mergeOld) {
            $this->_htmlOptions = CMap::mergeArray($this->_htmlOptions, $htmlOptions);
        } else {
            $this->_htmlOptions = $htmlOptions;
        }
        return $this;
    }

    /**
     * @param $tagName
     * @return LayoutBlock
     * @throws CException
     */
    public function setTagName($tagName)
    {
        if (!is_string($tagName)) {
            throw new CException(Yii::t('application', 'The block tag name must be a string.'));
        }
        $this->_tagName = $tagName;
        return $this;
    }

    /**
     * @param $visible
     * @return LayoutBlock
     * @throws CException
     */
    public function setVisible($visible)
    {
        if(is_string($visible)){
            $visible = $this->evaluateExpression($visible);
        }
        if (!is_bool($visible)) {
            throw new CException(Yii::t('application', 'The block visibility must be set to a boolean value.'));
        }
        $this->_visible = $visible;
        return $this;
    }

    /**
     * @param bool $return
     * @param bool $renderTag
     * @return string
     */
    public function render($return = false, $renderTag = true)
    {
        $block = $this->_content;
        if ($renderTag) {
            $block = CHtml::openTag($this->_tagName, CMap::mergeArray($this->_defaultHtmlOptions, $this->_htmlOptions)) . $block . CHtml::closeTag($this->_tagName);
        }
        if ($return === true) {
            return $block;
        } else {
            echo $block;
        }
    }
}
