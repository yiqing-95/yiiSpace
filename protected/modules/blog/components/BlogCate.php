<?php
Yii::import('zii.widgets.CPortlet');
/**
 * categories for specified user
 */
class BlogCate extends CPortlet
{

    public $title='BlogCategories';

    /**
     * @var int
     */
    public $uid = 0 ;

  public function getAllCategories()
  {
    return Category::model()->findAll('uid=:uid',array(':uid'=>$this->uid));
  }

  protected function renderContent()
  {
    $this->render('blogCate');
  }

}
