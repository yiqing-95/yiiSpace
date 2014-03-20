<?php
/**
 * EMBDropDownList.php
 *
 * Renders a dropdownlist of the menuitems, grouped by menu
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
Yii::import('ext.menubuilder.widgets.EMBBaseWidget');

class EMBDropDownList extends EMBBaseWidget
{
    const KEYS_SEPARATOR = '__';

    /**
     * The name of the dropDownList
     *
     * @var string
     */
    public $name = '';

    /**
     * The selected item of the dropDownList
     * @var string
     */
    public $select = '';

    /**
     * The htmlOptions for dropDownList
     * @var array
     */
    public $htmlOptions=array();

    /**
     * Wether to group by the menutitle
     *
     * @var bool
     */
    public $menuTitleAsOptGroup=true;

    /**
     * Wether to add the menuid to the key, separated by the KEYS_SEPARATOR
     *
     * @var bool
     */
    public $menuIdToKey=true;


    /**
     * Add the menu group
     *
     * @param $menu
     * @param $items
     * @param $language
     * @return array
     */
    public function addOptGroup($menu,$items,$language)
    {

        if(!empty($items) && $this->menuTitleAsOptGroup);
        {
            $optGroup = $this->getMenuBuilder()->getMenuTitle($menu, $language, false);
            return array($optGroup=>$items);
        }

        return $items;
    }

    /**
     * Get the (cached) items
     *
     * @return bool
     */
    public function getItems()
    {
        $cacheId = null;
        $items = $this->itemsFromCache($cacheId);
        if ($items !== false)
            return $items;

        $items = $this->getMenuBuilder()->getItemsProviderData('getDropDownListData',
            array(
                'htmlOptions'=>$this->htmlOptions,
                'labelDetailTemplate'=>$this->labelDetailTemplate,
                'menuIdToKey'=>$this->menuIdToKey,
                'keysSeparator'=>self::KEYS_SEPARATOR,
            ),
            $this->menuIds,
            null,//$mergeAsRootIds
            array('onMenuItems' => array($this,'addOptGroup')), //callbacks
            $this->getItemProviderOptions()
        );

        $this->itemsToCache($cacheId,$items);

        return $items;
    }

    /**
     * Render the dropDownList
     *
     * @return mixed|void
     */
    public function renderOutput()
    {
        $data = $this->getItems();
        $this->htmlOptions['encode']=false;
        echo CHtml::dropDownList($this->name,$this->select,$data,$this->htmlOptions);
    }


}