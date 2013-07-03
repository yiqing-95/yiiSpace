<?php
/**
 * EFrameJuiDlg renders a button/link or icon to display an iframe in a CJuiDialog
 *
 * Usage: see EQuickDlg::iframeButton, EQuickDlg::iframeLink, EQuickDlg::iframeIcon
 * Take a look at the attributes of the EBaseJuiDlg too.
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2012 myticket it-solutions gmbh
 * @license New BSD License
 * @version 1.2
 * @package ext.quickdlgs
 * @since 1.0
 */
class EFrameJuiDlg extends EBaseJuiDlg
{
    /**
     * Use this url for a request to a remote server
     * @var string
     */
    public $url;

    /**
     * Additional params for the url above or the controllerRoute
     * If combined with controllerRoute this params will be added by http_build_query
     * so there is no conflict with the urlmanager and the actionParams
     *
     * @var array
     */
    public $urlParams = array();

    /**
     * Use this for internal request
     * The default quickdlgs GET params will be added, so in a controllerAction additional scripts can be executed
     * @see EJuiDlgsColumn, EQuickDlgs::checkDialogJsScript(),EQuickDlgs::render()
     *
     * @var string
     */
    public $controllerRoute;

    /**
     * Additional params for the controllerRoute above
     * @var array
     */
    public $actionParams = array();

    /**
     * The htmlOptions for the iframe tag
     * @var array
     */
    public $iframeHtmlOptions = array();

    /**
     * Generate the url for the iframe
     *
     * @return string
     * @throws CException
     */
    protected function getActionUrl()
    {
        $url = $this->url;

        if (!empty($url)) //remote url
        {
            if(!empty($this->urlParams))
            {
              $paramChar = strpos($url,'?') === false ? '?' : '&';
              $url .= $paramChar . http_build_query($this->urlParams);
            }
        }
        else
        if(!empty($this->controllerRoute)) //internal url
        {
            //add the quickdlgs GET params
            $this->addDlgsParams($this->urlParams);

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
     * Get the id of the iframe tag
     * @return string
     */
    protected function getFrameId()
    {
        return $this->id . '-frame';
    }

    /**
     * render the iframe tag inside the dialog
     */
    public function renderDialogContent()
    {
        $url = $this->getActionUrl();
        $src = $this->isAutoOpen() ? $url : '';
        $htmlOptions = array_merge($this->iframeHtmlOptions, array('width' => '100%', 'height' => '100%', 'src' => $src, 'id' => $this->getFrameId()));
        echo CHtml::tag('iframe', $htmlOptions, CHtml::link('Link', $url));
    }

    /**
     * Set the source of the iframe
     * @return string
     */
    protected function jsBeforeOpenClick()
    {
        return parent::jsBeforeOpenClick() . "$('#{$this->getFrameId()}').attr('src','{$this->getActionUrl()}');";
    }
}
