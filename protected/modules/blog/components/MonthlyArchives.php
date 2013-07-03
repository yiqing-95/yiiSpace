<?php
  // @version $Id: MonthlyArchives.php 68 2010-08-20 09:43:02Z mocapapa@g.pugpug.org $

class MonthlyArchives extends CPortlet
{
  public $title='Archives';
  public $year = '年';
  public $month = '月';

    /**
     * @var int
     */
    public $uid = 0 ;

  public function findAllPostDate()
  {
    return Post::model()->findArchives($this->uid);
  }

  protected function renderContent()
  {
    $this->render('monthlyArchives');
  }

}
