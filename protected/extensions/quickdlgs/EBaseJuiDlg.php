<?php
/**
 * The base widget for EAjaxJuiDlg and EFrameJuiDlg
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2012 myticket it-solutions gmbh
 * @license New BSD License
 * @version 1.2
 * @package ext.quickdlgs
 * @since 1.0
 */
abstract class EBaseJuiDlg extends CWidget
{
    /**
     * The title of the dialog
     * @var string
     */
    public $dialogTitle;

    /**
     * The width of the dialog
     * @var integer
     */
    public $dialogWidth;

    /**
     * The height of the dialog
     * @var integer
     */
    public $dialogHeight;

    /**
     * Hide the titlebar of the dialog
     * @var integer
     */
    public $hideTitleBar = false;

    /**
     * The type of the open button: 'button' or 'link'
     * If the attribute openImageUrl below is set, this will be set to 'icon'
     *
     * @var string
     */
    public $openButtonType = 'button'; //'link'

    /*
     * The text of the dialog open button
     */
    public $openButtonText = 'Open dialog';

    /*
     * The htmlOptions of the dialog open button
     */
    public $openButtonHtmlOptions = array();

    /**
     * If assigned, the dialog open button will be rendered as icon
     * @var string
     */
    public $openImageUrl;

    /**
     * If not empty a close button will be added to the CJuiDialog
     * @var string
     */
    public $closeButtonText;

    /**
     * The wrapper tag for the content inside the dialog
     * @var string
     */
    public $contentWrapperTag = 'div';

    /**
     * The htmlOptions for the wrapper tag
     * @var array
     */
    public $contentWrapperHtmlOptions = array();

    /**
     * js-code to execute before '$().dialog("open")' in the button click
     * @var string
     */
    public $jsBeforeOpenDialog;

    /**
     * js-code to execute after '$().dialog("open")' in the button click
     * @var string
     */
    public $jsAfterOpenDialog;

    /**
     * The attributes of the CJuiDialog widget like theme, themeUrl, cssFile
     * @link http://www.yiiframework.com/doc/api/1.1/CJuiDialog
     * @var array
     */
    public $dialogAttributes = array();

    /**
     * If true, the GET param EQuickDlgs::URLPARAM_CLOSEONACTION will be added to the ajax/iframe url
     * This is checked in EQuickDlgs::checkDialogJsScript in a controller action
     * @var bool
     */
    public $closeOnAction = false;
    public $refreshGridId;

    /**
     * Should always be true to render a open dialog button
     * Is set to false EJuiDlgsColumn, where no button is needed
     * @var bool
     */
    public $renderOpenButton = true;

    /**
     * The default option for the JuiDialog options
     * @var array
     */
    protected $defaultJuiOptions = array(
        'width' => 580,
        'height' => 480,
        'autoOpen' => false,
        'modal' => true,
    );

    /**
     * Has to be implemented by the descendants
     *
     * @abstract
     * @return mixed
     */
    abstract protected function renderDialogContent();

    /**
     * Register the clientScript
     */
    protected function registerClientScript()
    {
       if($this->isAutoOpen())
       {
           $script = $this->getHideTitleBarScript();
           //Yii::app()->getClientScript()->registerScript(__CLASS__.'#Htb'.$this->id,$script,CClientScript::POS_READY);
       }
    }

    /**
     * Getter for the jsBeforeOpenDialog attribute
     * @return string
     */
    protected function jsBeforeOpenClick()
    {
        return $this->jsBeforeOpenDialog;
    }

    /**
     * Getter for the jsAfterOpenClick attribute
     * @return string
     */
    protected function jsAfterOpenClick()
    {
        return $this->jsAfterOpenDialog;
    }


    /**
     * Initialize the widget
     */
    public function init()
    {
        $this->registerClientScript();
    }


    /**
     * Get the dialog id for the htmlOptions
     *
     * @return string
     */
    public function getDialogId()
    {
        return $this->id . '-dlg';
    }

    /**
     * Get the id for content div inside the dialog
     * @return string
     */
    public function getContentWrapperId()
    {
        return $this->id . '-content';
    }

    /**
     * Get the jQuery selector js-code
     * @return string
     */
    public function getJQDialogSelector()
    {
        $dialogId = $this->getDialogId();
        return "$('#$dialogId')";
    }

    /**
     * Check if autoopen option is true
     * @return bool
     */
    public function isAutoOpen()
    {
        return isset($this->dialogAttributes['options']['autoOpen']) && $this->dialogAttributes['options']['autoOpen'];
    }

    /**
     * Get the js-code to open the dialog
     *
     * @param $method
     * @return string
     */
    public function getJsDialogMethod($method)
    {
        $script = $method == 'open' ? "$('#{$this->getContentWrapperId()}').show();" : '';
        $script .= $this->getJQDialogSelector() . ".dialog('$method');";

        $script .= $method == 'open' ? $this->getHideTitleBarScript() : '';

        $script .= 'return false;';

        return $script;
    }

    /**
     * Add the code to make the contentcontainer visible and to hide the titlebar
     * It's only done when the dialogAttributes['options']['open'] are not assigned
     */
    public function setOptionsOpen()
    {
       if(empty($this->dialogAttributes['options']['open']))
       {
           $script = "$('#{$this->getContentWrapperId()}').show();";

           if ($this->hideTitleBar)
               $script .= 'var dlgid=this.id; $("."+dlgid+" div.ui-dialog-titlebar").hide();';

           $script = 'js:function(event, ui) {'.$script.'}';
           $this->dialogAttributes['options']['open'] = $script;
       }
    }

    /**
     * Get the script to hide the titlebar
     * @return string
     */
    public function getHideTitleBarScript()
    {
        if ($this->hideTitleBar)
            return "$('.{$this->getDialogId()} div.ui-dialog-titlebar').hide();";

        return '';
    }


    /**
     * Generate the attributes for the CJuiDialog widget
     * @return array
     */
    protected function getDialogAttributes()
    {
        $juiOptions = isset($this->dialogAttributes['options']) ? $this->dialogAttributes['options'] : array();
        $this->dialogAttributes['options'] = array_merge($this->defaultJuiOptions, $juiOptions);

        $this->dialogAttributes['id'] = $this->getDialogId();
        $this->dialogAttributes['options']['dialogClass'] = $this->getDialogId();

        if(empty($this->dialogAttributes['options']))
            $this->dialogAttributes['options'] = array();

        //add closeButton
        if(!empty($this->closeButtonText))
        {
            if(empty($this->dialogAttributes['options']['buttons']))
                $this->dialogAttributes['options']['buttons'] = array();

            $this->dialogAttributes['options']['buttons'][$this->closeButtonText] = 'js:function() {$(this).dialog("close");}';
        }

        if(isset($this->dialogTitle))
        {
            $this->dialogAttributes['options']['title'] = $this->dialogTitle;
        }

        if(isset($this->dialogWidth))
        {
            $this->dialogAttributes['options']['width'] = $this->dialogWidth;
        }

        if(isset($this->dialogHeight))
        {
            $this->dialogAttributes['options']['height'] = $this->dialogHeight;
        }

        $this->setOptionsOpen();

        return $this->dialogAttributes;
    }



    /**
     * Add the quickdlgs specific GET params
     * @param $params
     */
    public function addDlgsParams(&$params)
    {
        if(!is_array($params))
            $params = array();

        $params[EQuickDlgs::URLPARAM_CLASS] = get_class($this);
        $params[EQuickDlgs::URLPARAM_DIALOGID] = $this->getDialogId();
        $params[EQuickDlgs::URLPARAM_CONTENTWRAPPERID] = $this->getContentWrapperId();

        if(method_exists($this,'getFrameId'))
            $params[EQuickDlgs::URLPARAM_IFRAMEID] = $this->getFrameId();

        if (!empty($this->refreshGridId))
            $this->urlParams[EQuickDlgs::URLPARAM_GRIDID] = $this->refreshGridId;

        if ($this->closeOnAction)
            $this->urlParams[EQuickDlgs::URLPARAM_CLOSEONACTION] = 1;
    }


    /**
     * Render a button, link or icon
     *
     * @param $config
     * @throws CException
     */
    public function renderButton($config)
    {
        $text = isset($config['text']) ? $config['text'] : 'Buttontext';
        $type = isset($config['type']) ? $config['type'] : 'button';
        $buttonHtmlOptions = isset($config['htmlOptions']) ? $config['htmlOptions'] : array();
        $imageUrl = isset($config['openImageUrl']) ? $config['openImageUrl'] : null;

        if (!empty($imageUrl))
            $type = 'icon';

        if ($type == 'icon')
        {
            $buttonHtmlOptions['style'] = isset($buttonHtmlOptions['style']) ? $buttonHtmlOptions['style'] . 'cursor:pointer;' : 'cursor:pointer;';
            echo CHtml::image($imageUrl, $text, $buttonHtmlOptions);
        } elseif ($type == 'button')
            echo CHtml::button($text, $buttonHtmlOptions);
        elseif ($type == 'link')
        {
            $url = method_exists($this,'getActionUrl') ? $this->getActionUrl() : '#';
            echo CHtml::link($text, $url, $buttonHtmlOptions);
        }
        else
            throw new CException('invalid buttontype: ' . $type);
    }

    /**
     * Render the open dialog button
     */
    public function renderDialogOpenButton()
    {
        $dialogId = $this->getDialogId();
        $onClick = $this->jsBeforeOpenClick() . $this->getJsDialogMethod('open') . $this->jsAfterOpenClick();
        $buttonHtmlOptions = array_merge($this->openButtonHtmlOptions, array('onclick' => $onClick));
        $config = array(
            'type' => $this->openButtonType,
            'text' => $this->openButtonText,
            'htmlOptions' => $buttonHtmlOptions,
            'openImageUrl' => $this->openImageUrl
        );
        $this->renderButton($config);
    }


    /**
     * Render the CJuiDialog
     */
    public function renderDialog()
    {
        $this->beginWidget('zii.widgets.jui.CJuiDialog', $this->getDialogAttributes());

        //render the container
        $htmlOptions = $this->contentWrapperHtmlOptions;
        $htmlOptions['id'] = $this->getContentWrapperId();

        //set visibility of the contentwrapper to false:
        //if javascript is disabled and 'flickering'
        //see the comment from yiimike at
        //http://www.yiiframework.com/wiki/262/cgridview-display-the-full-record-actionview-in-a-cjuidialog/
        //set visibility true before open (getJsDialogMethod)
        $htmlOptions['style'] = !empty($htmlOptions['style']) ? $htmlOptions['style'] . ' display:none' : 'display:none';

        echo CHtml::openTag($this->contentWrapperTag, $htmlOptions);
        $this->renderDialogContent();
        echo CHtml::closeTag($this->contentWrapperTag);

        $this->endWidget();
    }



    /**
     * Render the code for the CJuiDialog and the open dialog button
     */
    public function run()
    {
        $this->renderDialog();
        if ($this->renderOpenButton)
            $this->renderDialogOpenButton();
    }

}
