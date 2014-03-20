<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-28
 * Time: 下午5:32
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------
 * @see http://www.yiiframework.com/wiki/127/dynamic-sidebar-using-cclipwidget/
 * ---------------------------------------------------------
 * 网页布局经典书籍推荐：《Drupal® THE GUIDE TO PLANNING AND BUILDING WEBSITES》
 * -----------------------------------------------------------------------------
 * 默认最多 3列布局 ，布局区域：left right center top bottom 五个布局区域
 * 或者参考：http://layout.jquery-dev.net/demos.cfm
 * 布局区域分  west east center north south
 *
 * 我们采用第一种做法 主布局中可用的占位 如果存在left或者right 认为至少用二列布局
 * 如果同时存在left和right那么用三列布局
 * -----------------------------------------------------------------------------
 * 嵌套布局 可以参考GOF95 的组合设计模式来设计
 * -----------------------------------------------------------------------------
 * 该类直接扩展子CMap 也许更好 有机会重构吧！
 * -----------------------------------------------------------------------------
 * 可以结合此扩展 实现更灵活的布局！
 * @see http://www.yiiframework.com/extension/inline-widgets-behavior/
 */
class Layout
{
    /**
     * @var Layout
     */
    private static $_instance ;

    /**
     * @var CMap
     */
    protected $regions;

    /**
     *
      */
    public function __construct()
    {
        $this->regions = new CMap;
    }

    /**
     * @return CMap
     * -----------------------------
     * this is a debug method ,do not use this directly !
     * ------------------------------
     */
    public function getAllRegions(){
        return $this->regions ;
    }

    /**
     * @static
     * @return Layout
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * @static
     * @param $blockA
     * @param $blockB
     * @return int
     * @throws CException
     */
    protected static function compareBlocks($blockA, $blockB)
    {
        if ($blockA instanceof LayoutBlock && $blockB instanceof LayoutBlock) {
            if ($blockA->getWeight() === $blockB->getWeight()) {
                return 0;
            }
            return ($blockA->getWeight() <= $blockB->getWeight()) ? -1 : 1;
        } else {
            throw new CException(Yii::t('application', 'Both blocks must be instances of LayoutBlock or one of it\'s children.'));
        }
    }

    /**
     * @static
     * @param $blocks
     * @return CMap
     */
    protected static function sortBlocks($blocks)
    {
        $blocks = $blocks->toArray();

        uasort($blocks, array(__CLASS__, 'compareBlocks'));

        return new CMap($blocks);
    }

    /**
     * @param $regionId
     * @param bool $visibleOnly
     * @return CMap
     */
    static  public function getBlocks($regionId, $visibleOnly = true)
    {
        $instance = self::getInstance();

        $blocks = new CMap;

        if ($instance->regions->contains($regionId)) {
            foreach ($instance->regions[$regionId] as $blockId => $block) {
                if ($visibleOnly) {
                    if ($block->getVisible() === false) {
                        continue;
                    }
                }
                $blocks->add($blockId, $block);
            }
        }

        return self::sortBlocks($blocks);
    }

    /**
     * @static
     * @param $regionId
     * @param $blockData
     * @throws CException
     */
    public static function addBlock($regionId, $blockData)
    {
        if (isset($blockData['id'])) {
            $instance = self::getInstance();

            $blockId = $blockData['id'];
            $content = $blockData['content'];

            $weight = isset($blockData['weight']) ? $blockData['weight'] : 0;
            $visible = isset($blockData['visible']) ? $blockData['visible'] : true;
            $htmlOptions = isset($blockData['htmlOptions']) ? $blockData['htmlOptions'] : array();
            $tagName = isset($blockData['tagName']) ? $blockData['tagName'] : LayoutBlock::DEFAULT_BLOCK_TAG;

            $block = new LayoutBlock($blockId, $content, $weight, $visible, $htmlOptions);

            if (!$instance->regions->contains($regionId)) {
                $instance->regions[$regionId] = new CMap;
            }
            $instance->regions[$regionId]->add($blockId, $block);
        } else {
            throw new CException(Yii::t('application', 'A block must have at least an id.'));
        }
    }

    /**
     * @static
     * @param array $blocks
     */
    public static function addBlocks($blocks = array())
    {
        foreach ($blocks as $blockData) {
            if (isset($blockData['regionId'])) {
                $regionId = $blockData['regionId'];
                unset($blockData['regionId']);
                self::addBlock($regionId, $blockData);
            }
        }
    }

    /**
     * @static
     * @param $regionId
     * @param $blockId
     * @return null
     */
    public static function getBlock($regionId, $blockId)
    {
        $instance = self::getInstance();
        if (($region = self::getRegion($regionId)) !== null) {
            if ($region->contains($blockId)) {
                return $region[$blockId];
            }
        }
        return null;
    }

    /**
     * @static
     * @param $regionId
     * @param $blockId
     * @return bool
     */
    public static function hasBlock($regionId, $blockId)
    {
        return self::getBlock($regionId, $blockId) !== null;
    }

    /**
     * @static
     * @param $regionId
     * @param $blockId
     */
    public static function removeBlock($regionId, $blockId)
    {
        if (($region = self::getRegion($regionId)) !== null) {
            if ($region->contains($blockId)) {
                $region->remove($blockId);
            }
        }
    }

    /**
     * @static
     * @param $regionId
     * @return null
     */
    public static function getRegion($regionId)
    {
        $instance = self::getInstance();
        return $instance->regions->contains($regionId) ? $instance->regions[$regionId] : null;
    }

    /**
     * @static
     * @param $regionId
     * @return bool
     */
    public static function hasRegion($regionId)
    {
        return self::getRegion($regionId) !== null;
    }

    /**
     * @static
     * @return bool
     * @throws CException
     */
    public static function hasRegions()
    {
        $args = func_get_args();
        if (count($args)) {
            foreach ($args as $regionId) {
                if (!self::hasRegion($regionId)) {
                    return false;
                }
            }
            return true;
        }
        throw new CException(Yii::t('application', 'No region id was specified.'));
    }

    /**
     * @static
     * @param $regionId
     * @param bool $return
     * @return string
     */
    public static function renderRegion($regionId, $return = false)
    {
        $regionContent = '';

        if (self::hasRegion($regionId)) {
            $blocks = self::getBlocks($regionId);

            foreach ($blocks as $block) {
                if ($block instanceof LayoutBlock) {
                    $regionContent .= $block->render(true);
                }
            }
        }

        if ($return) {
            return $regionContent;
        } else {
            echo $regionContent;
        }
    }

    /**
     * @static
     * @param $regionId
     */
    public static function removeRegion($regionId)
    {
        $instance = self::getInstance();

        if ($instance->regions->contains($regionId)) {
            $instance->regions->remove($regionId);
        }
    }
    //-------------------------------------------------------------------------\\

    /**
     * @var array
     * the current blockConfig
     */
    static protected $blockConfig = array();
    /**
     * @var integer a counter used to generate IDs for blocks.
     */
    private static $_counter = 0;

    /**
     * @param $regionId
     * @param array $blockConfig
     * @throws CException
     * 可以方便的添加一个不用明确指定blog区域的块
     * 注意层级关系  Region has_many Block
     * 每一个blog在添加时候需要指定添加到那个region下
     */
    static public function beginBlock($regionId, $blockConfig=array()){
        if(!empty(self::$blockConfig)){
            throw new CException('you may not call the endBlock method of class '.__CLASS__.' properly !');
        }
        $blockConfig['regionId'] = $regionId ;
        self::$blockConfig = $blockConfig ;

        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * must be used together with Layout::beginBlock method
     */
    static public function endBlock(){
        $blockContent = ob_get_clean();
        if(empty($blockContent)){
           $blockContent = '';
        }
        $currentBlockConfig = self::$blockConfig;
        $regionId = $currentBlockConfig['regionId'];
        // unset(self::$blockConfig);
        self::$blockConfig = array() ;// reset it for next usage !
        unset($currentBlockConfig['regionId']);

        if(!isset($currentBlockConfig['id'])){
            $currentBlockConfig['id'] = 'block' . self::$_counter++;
        }
        $currentBlockConfig['content'] = $blockContent ;
        self::addBlock($regionId,$currentBlockConfig);
    }
    //-------------------------------------------------------------------------//
}