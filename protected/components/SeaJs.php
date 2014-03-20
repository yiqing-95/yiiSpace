<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-1-4
 * Time: 上午1:56
 */

class SeaJs extends CComponent {


    public static  function getSeaJsModuleUrl($seaJsModulePath = null){
        static $url  ;
        if($url === null){
            if($seaJsModulePath === null){
                $seaJsModulePath = implode(
                    DIRECTORY_SEPARATOR,
                    array(
                       //  dirname(Yii::getPathOfAlias('application')),
                        'public',
                        'sea-modules'
                    )
                );

                $url = Yii::app()->assetManager->publish($seaJsModulePath,false,-1,YII_DEBUG);
                return $url ;
            }
        }else{
            return $url ;
        }


    }
} 