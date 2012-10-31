<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 下午2:20
 * To change this template use File | Settings | File Templates.
 */
class HomePageBlock extends CWidget
{
    /**
     * @var string
     */
    public  $title = 'users';

    /**
     * @var string
     * not used , TbBox or FleetBoxWidget
     */
    public  $type = 'TbBox';

    /**
     * @var string
     */
    public $activeAction = 'tops';

    /**
     * @var string
     */
    protected  $blockHtmlId ;

    /**
     * @var string
     * js code on action lick clicked
     */
    protected $onActionClick = '';

    /**
     * @var array
     * additional options for the underlying TbBox widget
     */
    public $tbBoxOptions = array();
    /**
     *
     */
    public function run(){
        //===========================================================================
        //  request from ajax
        $request = Yii::app()->request;
        if($request->getIsAjaxRequest()){
            $actName = $request->getParam('act',false);
            if($actName !== false){
                $this->activeAction = $actName ;
                echo $this->getActiveContent() ;
                return ;
            }
        }
        //===========================================================================
        // $content = $this->getActiveContent();

        $this->blockHtmlId = get_class($this) . $this->getId();

        $contentId = 'content-'.$this->blockHtmlId ;
        $contentOptions = array('id'=> $contentId);

        $this->onActionClick = <<<ON_CLICK
         $("#{$contentId}").load($(this).attr("href")); return false ;
ON_CLICK;


        $defaultTbBoxOptions = array(
            'htmlOptions'=>array(
                'id'=>$this->blockHtmlId
            ),
            'title' => $this->title,
            'headerIcon' => 'icon-home',
            'content' => CHtml::tag('div',$contentOptions, $this->getActiveContent()),
            'headerButtonActionsLabel' => 'filters',

            'headerActions' => $this->getHeaderActions(),
        );

        // 主要是复写下面这个widget的创建过程
        $this->widget('bootstrap.widgets.TbBox', CMap::mergeArray($defaultTbBoxOptions,$this->tbBoxOptions));

        //$this->render('homeBlock2');
    }


    /**
     * @return array
     * -------------------------------------------
     * this method should be overwrite by children class
     * @see TbBox::headerActions
     * ---------------------------------------------------------
     *  'headerActions' => array(
          array('label'=>'tops', 'url'=>$this->createActionUrl('tops'), 'icon'=>'icon-music',
             'linkOptions'=>array('onclick'=>$this->onActionClick)),

          array('label'=>'latest', 'url'=>$this->createActionUrl('latest'), 'icon'=>'icon-headphones',
               'linkOptions'=>array('onclick'=>$this->onActionClick)),
                // '---',
         )
     * ------------------------------------------------------------
     *
     *
     */
    protected function getHeaderActions(){
        return array(

        );
    }

    /**
     * @param $actionName
     * @return string
     */
    public function createActionUrl($actionName){
        return $this->controller->createUrl('/'.$this->controller->route,array('act'=>$actionName));
    }

    /**
     * @return mixed
     * @throws CException
     */
    public function getActiveContent(){
        $methodName = 'action'.$this->activeAction;
        if(method_exists($this,$methodName)){
            ob_start();
            $return = $this->{$methodName}();
            $outputCache = ob_get_clean() ;
            return empty($outputCache) ? $return : $return.$outputCache ;

        }else{
            $className = get_class($this);
            throw new CException(" method {$methodName} does not exist in class {$className} ");
        }
    }
}
