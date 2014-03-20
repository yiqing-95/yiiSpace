<?php

Yii::import('bootstrap.widgets.TbListView');

class TbColumnView extends TbListView
{

    /**
     * @var array
     */
    public $pager = array('class' => 'bootstrap.widgets.TbPager',
        'alignment' => 'right',
    );

    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * @var int the number of columns to render in the table
     * Defaults to 5
     */
    public $cols = 5;

    /**
     * @var bool
     * whether to support ajax delete functionality as the CGridView
     * just give a "delete" class
     */
    public $enableAjaxDelete = false; //false;

    /**
     * @var array
     */
    public $cellCssClass = array('odd', 'even');
    /**
     * @var string
     */
    public $cellCssClassExpression;

    /**
     * @var array
     */
    public $rowHtmlOptions = array();

    /**
     * @var array
     */
    public $tableHtmlOptions = array();

    /**
     * @var array
     */
    public $cellHtmlOptionsExp = array();

    /**
     * @var string
     */
    public $keyField = 'id';
    //--------<css settings>---------------------------------------

    /**
     * @var string|array
     */
    public $gridCss ; //= '{background: #28dc94; border-spacing: 8px;}';

    /**
     * @var string|array
     */
    public $gridCellCss ;// = '{background: #d0f368;}';

    //--------<css settings/>---------------------------------------
    public function init()
    {
        $this->cs = Yii::app()->getClientScript();

        parent::init();

        if ($this->dataProvider instanceof CArrayDataProvider) {
            $this->keyField = $this->dataProvider->keyField;
        }

        // $this->handleCss();
    }

    /**
     * @param string $gridCssClass
     * @return TbColumnView
     */
    protected function handleCss($gridCssClass = 'MediaGrid')
    {
        /*
        // hack to override Yii's default of alternating table row colors. Should be moved to a css file with other proper formatting.
        //echo '<style type="text/css">tbody tr:nth-child(even) td, tbody tr.even td {background:#FFF;}</style>';
        echo '<style type="text/css">
                                .MediaGrid {background: #eedc94; border-spacing: 8px;}
                                .MediaGrid tbody tr td {background: #d0fc68;}
                        </style>';
          */
        $cssSettings = '';
        if (is_string($this->gridCss)) {
            $cssSettings .= '.' . $gridCssClass . $this->gridCss;
        } elseif (is_array($this->gridCss)) {
            $cssSettings .= '.' . $gridCssClass . $this->genCssFromArray($this->gridCss);
        }
        $cssSettings .= "\n";
        if (is_string($this->gridCellCss)) {
            $cellRule = '.' . $gridCssClass . ' tbody tr td ';
            $cssSettings .= $cellRule . $this->gridCellCss;
        } elseif (is_array($this->gridCellCss)) {
            $cellRule = '.' . $gridCssClass . ' tbody tr td ';
            $cssSettings .= $cellRule . $this->genCssFromArray($this->gridCellCss);
        }
        Yii::app()->getClientScript()->registerCss(__CLASS__ . '#' . $this->getId(), $cssSettings);
        return $this;
    }

    /**
     * Renders the data item list.
     */
    public function renderItems()
    {
        // echo '<table border="0" cellspacing="1" cellpadding="0" width="100%" class="MediaGrid">'."\n\t<tr>\n";
        $tableOptions = array(
            'border' => 0,
            'cellspacing' => 1,
            'cellpadding' => 0,
            'width' => '100%',
             'class' => 'MediaGrid',
        );
        if (!empty($this->tableHtmlOptions)) {
            $tableOptions = CMap::mergeArray($tableOptions, $this->tableHtmlOptions);
        }

        $this->handleCss($tableOptions['class']);

        echo CHtml::openTag('table', $tableOptions), "\n\t",
        CHtml::openTag('tr', $this->rowHtmlOptions), "\n";


        $data = $this->dataProvider->getData();

        if (count($data) > 0) {
            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';

            $width = (100 / $this->cols) . '%';

            foreach ($data as $i => $item) {
                $data = $this->viewData;
                $data['index'] = $i;
                $data['data'] = $item;
                $data['widget'] = $this;

                if ($i % $this->cols == 0 && $i != 0) {
                    echo CHtml::closeTag('tr'), "\n    ", CHtml::openTag('tr', $this->rowHtmlOptions), " \n";
                    //echo "    </tr>\n    <tr>\n";
                }
                //echo "\t\t<td class='thumb_square' id='thumb_square_$i' width='$width'>\n";

                $cellHtmlOptions = array('width' => '' . $width, 'id' => 'cell_' . $i);

                if ($this->cellCssClassExpression !== null) {
                    $cellHtmlOptions['class'] = '' . $this->evaluateExpression($this->cellCssClassExpression, array('i' => $i, 'data' => $item));
                } else
                    if (is_array($this->cellCssClass) && ($n = count($this->cellCssClass)) > 0) {
                        // $cellClass = array('class'=>''.$this->cellCssClass[$i % $n] );
                        $cellHtmlOptions['class'] = '' . $this->cellCssClass[$i % $n];
                    }

                //allow you to give a array to be evaluated on each item .
                if (!empty($this->cellHtmlOptionsExp) && is_array($this->cellHtmlOptionsExp)) {

                    $this->arrayWalk($this->cellHtmlOptionsExp, array('i' => $i, 'data' => $item));

                    $cellHtmlOptions = CMap::mergeArray($cellHtmlOptions, $this->cellHtmlOptionsExp);
                }

                echo "\t\t", CHtml::openTag('td', $cellHtmlOptions), "\n";
                $this->renderKey($item); // hidden the key string in every cell ,you can use js to fetch it !
                $owner->$render($this->itemView, $data);
                echo "\n\t\t", CHtml::closeTag('td'), "\n";
            }
        } else {
            $this->renderEmptyText();
        }
        echo  "\n\t</tr>\n</table>\n"; // "\n\t", CHtml::closeTag('tr'), "\n", CHtml::closeTag('table'), "\n";
        /**
         * when using openTag()/closeTag() they must not be coming as pairs !
         */
    }

    /**
     * @param $data
     * you  can add a click handler such as :
     * $( function(){
     * $("#xxx-grid > td").click(function(){
     *    $(this).find('.key').attr("title");
     * });
     * }
     * );
     */
    public function renderKey($data)
    {
        $key = '';
        /*
        if($data instanceof CActiveRecord){
            $key = $data->getPrimaryKey();
            if(is_array($key)){
                $key = CJSON::encode($key);
            }elseif(is_null($key)){
                $key = '';
            }
        }elseif(is_array($data) &&
            is_string($this->keyField) &&
            isset($data[$this->keyField])){
            $key = $data[$this->keyField];
        }*/

        if ($this->keyField === false) {
            $key = CJSON::encode($data);
        } else {
            $key = is_object($data) ? $data->getPrimaryKey() /* $data->{$this->keyField} */ : $data[$this->keyField];
            if (is_array($key)) {
                $key = CJSON::decode($key);
            } elseif (is_null($key)) {
                $key = '';
            }
        }
        echo CHtml::openTag('div', array(
            'class' => 'key',
            'style' => 'display:none',
            'title' => $key,
        )),
        "<span>", CHtml::encode($key), "</span>",
        "</div>\n";
    }


    /**
     * beforeDelete / afterDelete haven' t been realized now  , to be continue ....
     */
    public function registerClientScript()
    {
        parent::registerClientScript();
        //there is another delete functionality example : see view file of curd
        /**
         * if(confirm('Are you sure you want to delete this item?')) {jQuery.yii.submitForm(this,'/my/livmx/yq/delete/id/1',{});return false;} else return false;}
         * */
        // 注意跟gridview 共用时可能出现重复注册的现象！！
        $jsNameSpace = __CLASS__ ;
        $deleteContext = "#{$this->id}.{$this->itemsCssClass}";

        if ($this->enableAjaxDelete) {
            $jsCode = <<<DELETE_ITEM


jQuery(function(){
            if(typeof window.{$jsNameSpace} === "undefined"){
                  window.{$jsNameSpace} = {};
                  {$jsNameSpace}.deleteContexts = [];
            }
            if(jQuery.inArray("{$deleteContext}",{$jsNameSpace}.deleteContexts) !== -1){
                // alert("已经注册了删除事件！");
                return ;
            }else{
                {$jsNameSpace}.deleteContexts.push("{$deleteContext}");
                //alert("没有注册删除事件！");
            }
    jQuery('{$deleteContext} a.delete').live('click',function() {
        if(!confirm('确定要删除这条数据吗?')) return false;
        var th=this;
        var afterDelete=function(){};
        $.fn.yiiListView.update('{$this->id}', {
            type:'POST',
            url:$(this).attr('href'),
            success:function(data) {
                $.fn.yiiListView.update('{$this->id}');
                afterDelete(th,true,data);
            },
            error:function(XHR) {
                return afterDelete(th,false,XHR);
            }
        });
        return false;
    });
});

DELETE_ITEM;

            $this->cs->registerScript(__CLASS__ . '_' . $this->id . '_delete', $jsCode, CClientScript::POS_END);
        }
    }

    /**
     * use an array to generate  Css  code
     * @param array $cssSettings
     * @param bool $withCurlyBrace   whether close with curlyBrace |是否带上大括号返回
     * @return string
     * 根据数组生成css设置代码
     */
    public function genCssFromArray($cssSettings = array(), $withCurlyBrace = true)
    {
        $cssCodes = '';
        foreach ($cssSettings as $k => $v) {
            $cssCodes .= "{$k}:{$v}; \n";
        }
        if ($withCurlyBrace === true) {
            $cssCodes = '{' . "\n" . $cssCodes . '}';
        }
        return $cssCodes;
    }

    /**
     * parse the css code  to php array
     * @param string $cssString
     * @return array
     *
     * 从css代码 生成array 需要取掉两边的空格带大括号  如果有的话
     * 需要去除代码中的注释 找个工具方法或者网上搜索
     */
    public function getArrayFromCssString($cssString = '')
    {
        $rtn = array();
        //remove  {   and  }  if exists
        $cssString = rtrim(trim($cssString), '}');
        $cssString = ltrim($cssString, '{');
        //remove  all comments and space
        $text = preg_replace('!/\*.*?\*/!s', '', $cssString);
        $text = preg_replace('/\n\s*\n/', "", $text);
        // pairs handle
        $pairs = explode(';', $text);
        foreach ($pairs as $pair) {
            $colonPos = strpos($pair, ':');
            if (($k = trim(substr($pair, 0, $colonPos))) !== '') {
                $rtn[$k] = substr($pair, $colonPos + 1);
            }
        }
        return $rtn;
    }
}
