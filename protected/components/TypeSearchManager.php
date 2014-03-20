<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-26
 * Time: 上午1:44
 * To change this template use File | Settings | File Templates.
 */

class TypeSearchManager extends CApplicationComponent{

    public $typeHandlers = array(
        'blog'=> array(
            'class'=>'blog.components.BlogSearchHandler',
         ),
        'user'=> array(
            'class'=>'user.widgets.search.UserSearchHandler',
        ),
    );

    /**
     * @param string $type
     * @return ITypeSearchHandler
     */
    public function getSearchHandler($type=''){
         $typeConfig = array(
             'class'=>'blog.components.BlogSearchHandler',
         );
        if(isset($this->typeHandlers[$type])){
            $typeConfig = $this->typeHandlers[$type];
        }
         return Yii::createComponent($typeConfig);
    }

} 