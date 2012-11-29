<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-26
 * Time: 下午2:41
 * To change this template use File | Settings | File Templates.
 */
class EAjaxDelete extends CWidget
{

    /**
     * @var string
     * ---------------------
     * the gridViewId
     * ---------------------
     */
    public $gridViewId;

    /**
     * @var string
     * --------------------
     * the listViewId
     * --------------------
     */
    public $listViewId;

    /**
     * @var string
     * -----------------------
     * the list type , the CGridView and the CListView
     * both inherit from CBaseListView , so they are all
     * listView ;  this param used to determine the sub type of CBaseListView
     *
     * -----------------------
     */
    protected $listViewType = 'Grid';
    /**
     * @var string
     * ------------------------
     * the CGridView or CListView id
     * ------------------------
     */
    protected $updateId;

    /**
     * @var string
     * delete link
     */
    public $link;

    /**
     * @var
     */
    public $afterDelete;
    /**
     * @var string the confirmation message to be displayed when delete button is clicked.
     * By setting this property to be false, no confirmation message will be displayed.
     */
    public $deleteConfirmation;


    /**
     *
     */
    public function init()
    {
        if (!empty($this->gridViewId)) {
            $this->listViewType = 'Grid';
            $this->updateId = $this->gridViewId;
        } elseif (!empty($this->listViewId)) {
            $this->listViewType = 'List';
            $this->updateId = $this->listViewId;
        }
        parent::init();
    }


    public function run()
    {
        $deleteAction = $this->getJsCode();
        $jsCode = <<<INIT
       $("{$this->link}").live("click",function(e){
          e.preventDefault();
            {$deleteAction}
          return false ;
       });
INIT;


        Yii::app()->clientScript->registerScript(__CLASS__ . $this->link, $jsCode, CClientScript::POS_READY);
    }


    protected function getJsCode()
    {

        if (is_string($this->deleteConfirmation)) {
            $confirmation = "if(!confirm(" . CJavaScript::encode($this->deleteConfirmation) . ")) return false;";
        } else {
            $confirmation = '';
        }
        if (Yii::app()->request->enableCsrfValidation) {
            $csrfTokenName = Yii::app()->request->csrfTokenName;
            $csrfToken = Yii::app()->request->csrfToken;
            $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
        } else {
            $csrf = '';
        }

        if ($this->afterDelete === null) {
            $this->afterDelete = 'function(){}';
        }

        if ($this->updateId !== null) {
            $jsPluginName = "$.fn.yii{$this->listViewType}View";

            $jsCode = <<<EOD
	$confirmation
	var link = this;
	var afterDelete = $this->afterDelete;
	$jsPluginName.update('{$this->updateId}', {
		type:'POST',
		url:$(this).attr('href'),$csrf
		success:function(data) {
			$jsPluginName.update('{$this->updateId}');
			afterDelete(link,true,data);
		},
		error:function(XHR) {
			return afterDelete(link,false,XHR);
		}
	});
EOD;
        } else {
            $jsCode = <<<EOD
	$confirmation
	var link = this;
	var afterDelete = $this->afterDelete;
	$.ajax({
		type:'POST',
		url:$(this).attr('href'),$csrf
		success:function(data) {
			afterDelete(link,true,data);
		},
		error:function(XHR) {
			return afterDelete(link,false,XHR);
		}
	});
EOD;
        }
        return $jsCode;
    }
}
