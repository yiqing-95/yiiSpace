<?php
/**
 * EMBMenu.php
 *
 * The widget for rendering menus
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

Yii::import('ext.menubuilder.widgets.EMBBaseWidget');

class EMBMenu extends EMBBaseWidget
{
    /**
     * The class of the menu widget to render
     * Must be a descendant of CMenu
     *
     * @var string
     */
    public $menuClass;

    /**
     * The options/attributes for the menu class
     * @var array
     */
    public $menuOptions = array();

    /**
     * Wether to merge hardcoded items before or after the menubuilder items
     *
     * @var bool
     */
    public $menuBuilderItemsBefore = true;


    /**
     * Which menuids should be mapped as root item
     * You can add options to - see: http://www.yiiframework.com/doc/api/1.1/CMenu#items-detail
     * - If the options label is not set, the translated menu title will be used
     * - If the options items are set, these items will be merged with the items from the menubuilder
     * array('menuid1',
     *       'menuid2'=>array('visible'=>...,'linkOptions'....)
     *
     * @var array
     */
    public $rootMenuIds = array();

    /**
     * Used on merging a menu as root label
     *
     * @param $menu
     * @param $items
     * @param $language
     * @return array|null
     */
    public function getRootItem($menu, $items, $language)
    {
        $item = null;
        if (array_key_exists($menu->menuid, $this->rootMenuIds)) //assoziative
            $item = $this->rootMenuIds[$menu->menuid];
        elseif (in_array($menu->menuid, $this->rootMenuIds)) //not assoziative
            $item = array();

        if ($item === null)
            return null;

        if (!isset($item['label']))
            $item['label'] = $this->getMenuBuilder()->getMenuTitle($menu, $language, false);

        if (!isset($options['url']))
            $item['url'] = '#';

        if (isset($item['items']))
            $$item['items'] = $this->menuBuilderItemsBefore
                ? array_merge($items, $item['items'])
                : array_merge($item['items'], $items);
        else
            $item['items'] = $items;

        return $item;
    }


    /**
     * Get the items from the menubuilder and merge hardcoded items
     *
     * @return array|bool
     */
    public function getItems()
    {
        $cacheId = null;
        $items = $this->itemsFromCache($cacheId);
        if ($items !== false)
            return $items;

        if (!empty($this->rootMenuIds) && is_string($this->rootMenuIds))
            $this->rootMenuIds = array($this->rootMenuIds);

        $items = $this->getMenuBuilder()->getItemsProviderData('getCMenuItems',
            array(
                'labelDetailTemplate' => $this->labelDetailTemplate,
            ),
            $this->menuIds,
            $this->rootMenuIds,
            array('onGetRootItem' => array($this, 'getRootItem')), //callbacks
            $this->getItemProviderOptions()
        );

        if (!empty($this->menuOptions['items']))
        {
            $items = $this->menuBuilderItemsBefore
                ? array_merge($items, $this->menuOptions['items'])
                : array_merge($this->menuOptions['items'], $items);
        }

        $this->itemsToCache($cacheId, $items);

        return $items;
    }


    /**
     * Render the menu output
     *
     * @throws CException
     */
    public function renderOutput()
    {
        if (!isset($this->menuClass))
            throw new CException('No widgetClass assigned');

        $items = $this->getItems();
        if (!empty($items) || !empty($this->menuOptions['items']))
        {
            $this->menuOptions['items'] = $items;

            if (!isset($this->menuOptions['encodeLabel']))
                $this->menuOptions['encodeLabel'] = false; //handle internal because of the icons

            $this->widget($this->menuClass, $this->menuOptions);
        }
    }

}