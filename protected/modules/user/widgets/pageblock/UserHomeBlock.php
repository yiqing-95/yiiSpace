<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 上午10:45
 * To change this template use File | Settings | File Templates.
 */
class UserHomeBlock extends CWidget
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

        $id = get_class($this) . $this->getId();

        $contentId = 'content-'.$id ;
        $contentOptions = array('id'=> $contentId);

        $actionOnClick = <<<ON_CLICK
         $("#{$contentId}").load($(this).attr("href")); return false ;
ON_CLICK;


        // 主要是复写下面这个widget的创建过程
        $this->widget('bootstrap.widgets.TbBox', array(
            'htmlOptions'=>array(
              'id'=>$id
            ),
            'title' => $this->title,
            'headerIcon' => 'icon-home',
            'content' => CHtml::tag('div',$contentOptions, $this->getActiveContent()),
            'headerButtonActionsLabel' => 'filters',

            'headerActions' => array(
                array('label'=>'tops', 'url'=>$this->createActionUrl('tops'), 'icon'=>'icon-music',
                 'linkOptions'=>array('onclick'=>$actionOnClick)),

                array('label'=>'latest', 'url'=>$this->createActionUrl('latest'), 'icon'=>'icon-headphones',
                    'linkOptions'=>array('onclick'=>$actionOnClick)),
               // '---',
              //  array('label'=>'third action', 'url'=>'#', 'icon'=>'icon-facetime-video')
            )
        ));

        //$this->render('homeBlock2');
    }

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

    public function actionTops(){
        return __METHOD__ ;
    }

    public function actionLatest(){
       return __METHOD__ ;
    }
}
