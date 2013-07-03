<?php
/**
 * EAjaxJuiDlg renders a button/link or icon to display ajax content in a CJuiDialog
 *
 * Usage: see EQuickDlg::ajaxButton, EQuickDlg::ajaxLink, EQuickDlg::ajaxIcon
 * Take a look at the attributes of the EBaseJuiDlg too.
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2012 myticket it-solutions gmbh
 * @license New BSD License
 * @version 1.2
 * @package ext.quickdlgs
 * @since 1.0
 */


class EAjaxJuiDlg extends EBaseJuiDlg
{
    /**
     * Usage 'url'=>Yii::app()->createUrl(...)
     * If not empty, this will be used instead the controllerRoute
     * @var string
     */
    public $url;

    /**
     * GET params for the url above or the controllerRoute
     * If combined with controllerRoute this params will be added by http_build_query
     * so there is no conflict with the urlmanager and the actionParams
     *
     * @var array
     */
    public $urlParams = array();

    /**
     * The controllerRoute for the ajax request
     * If assigned with no '/', the id of the current controller will be added
     * Usage: 'view', 'create', 'site/view' ...
     *
     * @var string
     */
    public $controllerRoute;

    /**
     * The params for the controllerAction: array('id'=>$model->id) ...
     * @var array
     */
    public $actionParams = array();

    /**
     * Set additional ajax options here: see CHtml::ajax()
     * Example: array('cache'=>true)
     *
     * @var array
     */
    public $ajax = array();


    /**
     * Generate the url for the ajax request
     *
     * @return string
     * @throws CException
     */
    protected function getActionUrl()
    {
        $url = $this->url;

        //add the quickdlgs GET params
        $this->addDlgsParams($this->urlParams);

        if (!empty($url))
        {
            $paramChar = strpos($url,'?') === false ? '?' : '&';
            $url .= $paramChar . http_build_query($this->urlParams);
        }
        else
        if(!empty($this->controllerRoute))
        {
            $route = $this->getControllerRoute();
            $url = Yii::app()->createUrl($route, $this->actionParams);
            $url .= '?' . http_build_query($this->urlParams);
        }
        else
            throw new CException('Url or controllerRoute attribute missing');

        return $url;
    }

    /**
     * Generate the controllerRoute
     * Add the id of the current controller if the string contains no '/'
     *
     * @return string
     */
    public function getControllerRoute()
    {
        if(!empty($this->controllerRoute))
        {
            $route =  $this->controllerRoute;
            if (strpos($route, '/') === false)
                $route = Yii::app()->controller->id . '/' . $route;
        }
        else
            $route = Yii::app()->controller->id;

        return $route;
    }

    /**
     * Register the clientScript
     */
    protected function registerClientScript()
    {
        parent::registerClientScript();

        if($this->renderOpenButton)
        {
            $ajax = $this->ajax;
            $url = $this->getActionUrl();
            if(!empty($url))
                $ajax['url'] = $url;

            $ajax['update'] = '#' . $this->getContentWrapperId();
            $ajaxScript = "function {$this->getAjaxFunction()}{" . CHtml::ajax($ajax) . '}';
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$ajaxScript,CClientScript::POS_BEGIN);
        }
    }

    /**
     * Get the name of the registered ajaxFunction
     * @return string
     */
    protected function getAjaxFunction()
    {
        return str_replace('-','',$this->id). 'Ajax()';
    }

    /**
     * No need to render content by default
     */
    public function renderDialogContent()
    {
        //do nothing
    }

    /**
     * Add the code for the button click
     * @return string
     */
    protected function jsBeforeOpenClick()
    {
      return $this->renderOpenButton ? parent::jsBeforeOpenClick().$this->getAjaxFunction().';' : parent::jsBeforeOpenClick();
    }

}
