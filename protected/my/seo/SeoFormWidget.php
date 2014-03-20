<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-11
 * Time: 下午2:05
 */

/**
 * 渲染片段seo视图  一般会包裹在主实体的修改或者创建表单中！
 *
 * 可以参考这个：
 * @see https://github.com/segoddnja/YiiSEOBehavior
 *
 * Class SeoFormWidget
 */
class SeoFormWidget extends YsWidget{

    /**
     * 可以使用别名复盖掉
     *
     * @var string
     */
    public $view = 'seoForm';

    /**
     * SEO 作用到的实体对象名称 一般用get_class($model)
     *
     * @var string
     */
    public $seobleType ;

    /**
     * SEO 作用的实体id
     *
     * 如 $model->primaryKey ;
     * 如果主键是数组： implode ('.', $model->primaryKey);
     * 但这样要求此类型是字符串型  那就在想法把字符串变整形
     * 如果主键是数组那么考虑修改seoble_id 为字符串型 或者两个表一个字符串 一个为整数
     *
     * 在极端情况下 可以每个实体类对应一个seo表！
     * @var string|int
     */
    public $seobleId ;


    /**
     * 是不是创建
     *
     * 用seo作用到的实体对象的这个方法可以决定： $model->getIsNewRecord()
     *
     * @var bool
     */
    public $isNew =  true ;

    /**
     * @var Seo
     */
    protected  $seoModel ;

    /**
     * 作为内嵌到其他表单中的输入部分
     *
     * @var bool
     */
    public $onlyInputs = true ;


    /**
     * @var CActiveForm
     */
    public $parentForm  ;

    public function run(){

        if($this->isNew){
            $this->seoModel = new Seo();
        }else{
            // 可有外部传入 用has_one 关系 with查询下seo表
            if(null == $this->seoModel){
                $this->seoModel =  Seo::model()->findByAttributes(
                    array(
                        'seoble_type'=>$this->seobleType,
                        'seoble_id'=>$this->seobleId,
                    )
                );

               if(null == $this->seoModel){
                   $this->seoModel = new Seo() ;
               }
            }
        }

        if($this->seoModel->getIsNewRecord()){
            $this->seoModel->seoble_type = $this->seobleType;
            $this->seoModel->seoble_id = $this->seobleId ;
        }
        // 收集表单提交过了的数据 或者由外部传入？？
        if(isset($_POST['Seo'])){
            $this->seoModel->attributes = $_POST['Seo'];
        }

        $this->renderFormInputs() ;

    }


    public function beginForm(){
        throw new Exception('not implemented yet! method is '.__METHOD__) ;
    }

    public function renderFormInputs(){
        if(empty($this->parentForm)){
            $this->parentForm = new CActiveForm() ;
        }

        $this->render($this->view,array(
            'model'=>$this->seoModel,
            'form'=>$this->parentForm ,
        ));
    }

    public function endForm(){
        throw new Exception('not implemented yet! method is '.__METHOD__) ;
    }
} 