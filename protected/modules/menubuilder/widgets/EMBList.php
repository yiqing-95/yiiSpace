<?php
/**
 * EMBList.php
 *
 * The widget for rendering menuitems as nested tags
 * Default: renders a unordered list
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

Yii::import('ext.menubuilder.widgets.EMBBaseWidget');

class EMBList extends EMBBaseWidget
{
    /**
     * The wrapper tags and htmlOptions for the tags
     */
    public $wrapperTag = 'div';
    public $wrapperHtmlOptions = array();
    public $titleTag='h4';
    public $titleHtmlOptions = array();
    public $listTag = 'ul';
    public $itemTag = 'li';
    public $listHtmlOptions = array();
    public $itemHtmlOptions = array();
    public $linkHtmlOptions = array();

    /**
     * @var bool
     */
    public $wrapLink = true;

    public function wrapMenu($menu,$items,$language)
    {
       if(!empty($this->wrapperTag))
       {
         if(!empty($this->titleTag))
         {
             $title=$this->getMenuBuilder()->getMenuTitle($menu, $language, false);
             $items = CHtml::tag($this->titleTag,$this->titleHtmlOptions,$title) . $items;
         }

         $items = CHtml::tag($this->wrapperTag,$this->wrapperHtmlOptions,$items);
       }

       return $items;
    }


    /**
     * @param $module
     */
    public function getItems()
    {
        $cacheId = null;
        $items = $this->itemsFromCache($cacheId);
        if ($items !== false)
            return $items;

        $items = $this->getMenuBuilder()->getItemsProviderData('getListData',
            array(
                'listTag'=>$this->listTag,
                'itemTag'=>$this->itemTag,
                'listHtmlOptions'=>$this->listHtmlOptions,
                'itemHtmlOptions'=>$this->itemHtmlOptions,
                'wrapLink'=>$this->wrapLink,
                'linkHtmlOptions'=>$this->linkHtmlOptions,
                'labelDetailTemplate'=>$this->labelDetailTemplate,
            ),
            $this->menuIds,
            null,
            array('onMenuItems' => array($this,'wrapMenu')), //callbacks
            $this->getItemProviderOptions()
        );

        $this->itemsToCache($cacheId,$items);

        return $items;
    }


    /**
     * @return string
     */
    public function getCachePrefix()
    {
        $cachePrefix = md5($this->menuWidget . serialize($this->menuOptions));
        return $cachePrefix;
    }


    /**
     * @param $cachedId
     * @return bool
     */
    public function renderOutput()
    {
        echo $this->getItems();
    }


}