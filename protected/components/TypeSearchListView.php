<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-28
 * Time: 上午10:12
 * To change this template use File | Settings | File Templates.
 */
Yii::import('zii.widgets.CListView');
/**
 * 该类主要用来渲染搜索的结果数据提供者EsDataProvider
 * 调用某个类型的搜索渲染方法（作为回调设置给此类实例）
 *
 * Class TypeSearchListView
 */
class TypeSearchListView extends  CListView {

    /**
     * @var array|string  callable config
     *
     */
    public $itemRender;

    /**
     * Initializes the list view.
     * This method will initialize required property values and instantiate {@link columns} objects.
     */
    public function init()
    {
        // 默认的实现是必须传递 itemView的 但我们其实并不需要
        if($this->itemView===null){
              $this->itemView = '';
            // throw new CException(Yii::t('zii','The property "itemView" cannot be empty.'));
        }
        if($this->itemRender ===null){
            throw new CException(Yii::t('zii','The property "itemRender" cannot be empty.'));
        }
        parent::init();


    }

    /**
     * Renders the data item list.
     */
    public function renderItems()
    {
        echo CHtml::openTag($this->itemsTagName,array('class'=>$this->itemsCssClass))."\n";
        $data=$this->dataProvider->getData();
        if(($n=count($data))>0)
        {
            /*
             * 原有实现在这里 已注释掉了
            $owner=$this->getOwner();
            $viewFile=$owner->getViewFile($this->itemView);
            $j=0;
            foreach($data as $i=>$item)
            {
                $data=$this->viewData;
                $data['index']=$i;
                $data['data']=$item;
                $data['widget']=$this;
                $owner->renderFile($viewFile,$data);
                if($j++ < $n-1)
                    echo $this->separator;
            }
            */

            $j=0;
            foreach($data as $i=>$item)
            {
                $data=$this->viewData;
                $data['index']=$i;
                $data['data']=$item;
                $data['widget']=$this;

                if(is_string($this->itemRender)){
                    call_user_func($this->itemRender,$data);
                } elseif(is_callable($this->itemRender,true)) {
                    if(is_array($this->itemRender))
                    {
                        // an array: 0 - object, 1 - method name
                        list($object,$method)=$this->itemRender;
                        if(is_string($object))	// static method call
                            call_user_func($this->itemRender,$data);
                        elseif(method_exists($object,$method))
                            $object->$method($data);
                        else
                            throw new CException(Yii::t('yii','Event "{class}" is attached with an invalid itemRender "{handler}".',
                                array('{class}'=>get_class($this), '{handler}'=>$this->itemRender[1])));
                    }
                    else // PHP 5.3: anonymous function
                        call_user_func($this->itemRender,$data);
                }

                if($j++ < $n-1){
                    echo $this->separator;
                }
            }

        }
        else
            $this->renderEmptyText();
        echo CHtml::closeTag($this->itemsTagName);
    }

} 