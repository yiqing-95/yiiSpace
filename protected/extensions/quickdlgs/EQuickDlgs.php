<?php
/**
 * EQuickDlgs provides static methods to a button/link or icon to display ajax, iframe or simple content in a CJuiDialog
 *
 * By default the attributes for the CJuiDialog are loaded from ext.quickdlg.config.jumodal.php
 *
 * Additionally this component provides the functions render, checkIFrameLayout, checkCloseUpdateDialog
 * for changes in the view and update actions of a controller
 *
 * Usage:
 *
        EQuickDlgs::ajaxButton(
                    array(
                            'controllerRoute' => 'view',
                            'actionParams' => array('id'=>$model->id),
                            'dialogTitle' => 'The dialog title',
                            'dialogWidth' => 800,
                            'dialogHeight' => 600,
                            'openButtonText' => 'Show record',
                            //'closeButtonText' => 'Close', //uncomment to add a closebutton to the dialog
                    )
        );
 *
 *      Use EQuickDlgs::render in the actionView of the controller instead of
 *      the controller method 'render'. This will not render the full page
 *      if the content has to be rendered as ajax-reponse for the dialog. This will use 'renderPartial'

        public function actionView($id)
        {
           EQuickDlgs::render('view',array('model'=>$this->loadModel($id)));
           //$this->render('view',array('model'=>$this->loadModel($id)));
        }
 *
 *      Changes to the action update if used in an iframe or EJuiDlgsColumn:
 *
        public function actionUpdate($id)
        {
            ....

            if(model->save())
            {
                //close the dialog and update the grid instead of redirect if called by the update-dialog
                EQuickDlgs::checkDialogJsScript();
                $this->redirect(array('admin','id'=>$model->id));
            }

            EQuickDlgs::render('update',array('model'=>$model));
            //$this->render('update',array('model'=>$model));
        }

 *
 *     Changes to the action create if used in an iframe or EJuiDlgsColumn: The same as above
 *
        public function actionCreate()
        {
                ....

                if(model->save())
                {
                    //close the dialog and update the grid instead of redirect if called by the create-dialog
                    EQuickDlgs::checkDialogJsScript();
                    $this->redirect(....);
                }

                EQuickDlgs::render('create',array('model'=>$model));
                //$this->render('create',array('model'=>$model));
        }
 *
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2012 myticket it-solutions gmbh
 * @license New BSD License
 * @version 2.0
 * @package ext.quickdlgs
 * @since 1.0
 */
class EQuickDlgs
{
    const STDDIALOGATTRIBUTES = 'juimodal';

    const TYPECONTENT = 'content';
    const TYPEAJAX = 'ajax';
    const TYPEIFRAME = 'iframe';

    const URLPARAM_CLASS = 'qdsClass';
    const URLPARAM_DIALOGID = 'qdsDialogId';
    const URLPARAM_CONTENTWRAPPERID = 'qdsContentWrapperId';
    const URLPARAM_IFRAMEID = 'qdsIFrameId';
    const URLPARAM_GRIDID = 'qdsGridId';
    const URLPARAM_CLOSEONACTION = 'qdsCloseDialog';
    const URLPARAM_HIDETITLEBAR = 'qdsHideTitle';

    const EXTINSTALLDIR = 'ext.quickdlgs';


    /**
     * Render a open ajax-dialog button.
     * The dialog attributes are initialized by default from the array returned from 'ext.quickdlgs.config.juimodal'
     * Usage:
     *
        EQuickDlgs::ajaxButton(
                array(
                        'controllerRoute' => 'view',
                        'actionParams' => array('id'=>30),
                        'dialogTitle' => 'DetailView',
                        'dialogWidth' => 800,
                        'dialogHeight' => 600,
                        'openButtonText' => 'Show record',
                        'closeButtonText' => 'Close',
                        //'openButtonHtmlOptions' => array(...),
                      )
                );

     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function ajaxButton($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogButton('button',self::TYPEAJAX,$attributes,$defaultConfig);
    }

    /**
     * Render a open ajax-dialog link
     * Usage: see ajaxButton
     *
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function ajaxLink($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogButton('link',self::TYPEAJAX,$attributes,$defaultConfig);
    }

    /**
     * Render a open ajax-dialog icon
     * Usage: see ajaxButton with the additional parameter $imageUrl for the icon image
     *
     * @static
     * @param $imageUrl
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function ajaxIcon($imageUrl,$attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogIcon($imageUrl,self::TYPEAJAX,$attributes,$defaultConfig);
    }


    /**
     * Render an autoopen ajax-dialog
     *
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function ajaxPopup($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::renderPopup($attributes, self::TYPEAJAX, $defaultConfig);
    }

    public static function renderPopup($attributes, $type, $defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        $widgetAttributes = self::getDialogWidgetAttributes($attributes, $defaultConfig);
        $widgetAttributes['dialogAttributes']['options']['autoOpen'] = true;
        $widgetAttributes['renderOpenButton'] = false;
        self::renderDialogWidget($widgetAttributes, $type);
    }

    /**
     * Render a open iframe-dialog button
     *
     * Usage:
     *
            EQuickDlgs::iframeButton(
                    array(
                            'controllerRoute' => 'index',
                            'dialogTitle' => 'Recordlist',
                            'dialogWidth' => 800,
                            'dialogHeight' => 600,
                            'openButtonText' => 'List records',
                            'closeButtonText' => 'Close',
                            //'openButtonHtmlOptions' => array(...),
                    )
            );
     *
            EQuickDlgs::iframeButton(
                                array(
                                'url' => 'http://www.yiiframework.com',
                                'dialogTitle' => 'Yii',
                                'dialogWidth' => 800,
                                'dialogHeight' => 600,
                                'openButtonText' => 'Show Yii',
                                //'closeButtonText' => 'Close',
                                )
            );

     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function iframeButton($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogButton('button',self::TYPEIFRAME,$attributes,$defaultConfig);
    }

    /**
     * Renders a open iframe-dialog link
     * Usage: see iframeButton
     *
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function iframeLink($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogButton('link',self::TYPEIFRAME,$attributes,$defaultConfig);
    }

    /**
     * Render a open iframe-dialog icon
     * Usage: see iframeButton with the additional parameter $imageUrl for the icon image
     *
     * @static
     * @param $imageUrl
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function iframeIcon($imageUrl,$attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogIcon($imageUrl,self::TYPEIFRAME,$attributes,$defaultConfig);
    }


    /**
     * Render an autoopen iframe-dialog
     *
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function iframePopup($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::renderPopup($attributes, self::TYPEIFRAME, $defaultConfig);
    }

    /**
     * Render a open content-dialog button
     *
     * Note: the content is loaded when the view page is loaded
     * If you need 'performance' on loading the page, use the ajaxButton instead
     *
     * Usage:
     *
            EQuickDlgs::iframeButton(
                                array(
                                        'content' => 'Hello world',  //$this->renderPartial('_welcome')
                                        'dialogTitle' => 'Welcome',
                                        'dialogWidth' => 200,
                                        'dialogHeight' => 300,
                                        'openButtonText' => 'Show welcome',
                             )
            );
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function contentButton($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogButton('button',self::TYPECONTENT,$attributes,$defaultConfig);
    }

    /**
     * Render a open content-dialog link
     * Usage: see contentButton
     *
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function contentLink($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogButton('link',self::TYPECONTENT,$attributes,$defaultConfig);
    }

    /**
     * Render a open content-dialog icon
     * Usage: see contentButton  with the additional parameter $imageUrl for the icon image
     *
     * @static
     * @param $imageUrl
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function contentIcon($imageUrl,$attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::dialogIcon($imageUrl,self::TYPECONTENT,$attributes,$defaultConfig);
    }

    /**
     * Render an autoopen content-dialog
     *
     * @static
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function contentPopup($attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        self::renderPopup($attributes, self::TYPECONTENT, $defaultConfig);
    }



    /**
     * Internal rendering of a dialog button or link
     *
     * @static
     * @param $buttonType
     * @param $dialogType
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function dialogButton($buttonType,$dialogType,$attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        $widgetAttributes = self::getDialogWidgetAttributes($attributes,$defaultConfig);
        $widgetAttributes['openButtonType'] = $buttonType;
        self::renderDialogWidget($widgetAttributes,$dialogType);
    }

    /**
     * Internal rendering of a dialog icon
     *
     * @static
     * @param $imageUrl
     * @param $dialogType
     * @param $attributes
     * @param string $defaultConfig
     */
    public static function dialogIcon($imageUrl,$dialogType,$attributes,$defaultConfig=self::STDDIALOGATTRIBUTES)
    {
        $widgetAttributes = self::getDialogWidgetAttributes($attributes,$defaultConfig);
        $widgetAttributes['openImageUrl'] = $imageUrl;
        self::renderDialogWidget($widgetAttributes,$dialogType);
    }

    /**
     * Build the attributes for the widget
     * Merge the attributes from the function call and the config file ('ext.quickdlgs.config.juimodal' by default)
     *
     * @static
     * @param $attributes
     * @param $defaultConfig
     * @return array
     * @throws CException
     */
    public static function getDialogWidgetAttributes($attributes,$defaultConfig)
    {

        $path = strpos($defaultConfig,'.')===false ? self::EXTINSTALLDIR.'.config.'.$defaultConfig : $defaultConfig;
        $configFile = Yii::getPathOfAlias($path) . '.php';

        if(!is_file($configFile))
            throw new CException('Configfile not found: ' . $configFile);

        $defaultAttributes = include($configFile);

        return CMap::mergeArray($defaultAttributes,$attributes);
    }

    /**
     * Render the dialog widget
     *
     * @static
     * @param $attributes
     * @param $type
     * @throws CException
     */
    public static function renderDialogWidget($attributes, $type)
    {
        switch ($type)
        {
            case self::TYPECONTENT:
                Yii::app()->controller->widget(self::EXTINSTALLDIR.'.EContentJuiDlg',$attributes);
                break;
            case self::TYPEAJAX:
                Yii::app()->controller->widget(self::EXTINSTALLDIR.'.EAjaxJuiDlg',$attributes);
                break;
            case self::TYPEIFRAME:
                Yii::app()->controller->widget(self::EXTINSTALLDIR.'.EFrameJuiDlg',$attributes);
                break;
            default:
                throw new CException('Invalid dialog type: '.$type);
        }
    }


    /*
     * Check if the quickdlgs GET param URLPARAM_CLASS isset
     */
    public static function isDialogRequest()
    {
        return isset($_GET[self::URLPARAM_CLASS]) ? $_GET[self::URLPARAM_CLASS] : false;
    }

    /*
     * Check if the quickdlgs GET param URLPARAM_IFRAMEID isset
     */
    public static function isIFrameRequest()
    {
        return isset($_GET[self::URLPARAM_IFRAMEID]) ? $_GET[self::URLPARAM_IFRAMEID] : false;
    }


    /**
     * Render partial if a request comes from a dialog (the necessary quickdlgs GET params are set)
     *
     * @static
     * @param $view
     * @param array $data
     * @param bool $return
     * @param bool $processOutput
     * @param null $iframeLayout
     * @param null $controller
     * @return bool
     */
    public static function renderPartial($view,$data=array(),$return=false,$processOutput=false, $iframeLayout=null,&$controller=null)
    {
        if(!isset($iframeLayout))
             $iframeLayout = self::EXTINSTALLDIR . '.layouts.iframe';

        if(!isset($controller))
            $controller = Yii::app()->controller;

        $class = self::isDialogRequest();

        if($class && !self::checkIFrameLayout($controller,$iframeLayout))
        {
            $output = $controller->renderPartial($view,$data,$return,$processOutput);

            if (Yii::app()->request->isAjaxRequest)
            {
                echo $output;

                //js-code to open the dialog after submitted the ajax content
                if($class=='EJuiDlgsColumn')
                {
                    $dialogId = $_GET[self::URLPARAM_DIALOGID];

                    $script = "$('#$dialogId').dialog('open');";

                    if(isset($_GET[self::URLPARAM_HIDETITLEBAR]))
                        $script .= "$('.$dialogId div.ui-dialog-titlebar').hide();";

                    echo CHtml::script($script);
                }

                Yii::app()->end();
            }

            return $return ? $output : true;
        }

        return false;
    }

    /**
     * Render partial if a request comes from a dialog (the necessary quickdlgs GET params are set)
     * Usage: see header comment
     *
     * @static
     * @param $view
     * @param array $data
     * @param bool $return
     * @param null $iframeLayout
     * @param null $controller
     * @return bool
     */
    public static function render($view,$data=array(),$return=false,$iframeLayout=null,$controller=null)
    {
        if(($result=self::renderPartial($view,$data,$return,true,$iframeLayout,$controller)) === false)
           return $controller->render($view,$data,$return);
        else
           return $result;
    }

    /**
     * Set the layout to the iframe-layout if it is an iframe-dialog request in the controller action
     *
     * @static
     * @param $controller
     * @param null $iframeLayout
     * @return bool
     */
    public static function checkIFrameLayout(&$controller,$iframeLayout=null)
    {

        if(self::isIFrameRequest())
        {
            if(!isset($iframeLayout))
             $iframeLayout = self::EXTINSTALLDIR . '.layouts.iframe';

            $controller->layout=$iframeLayout;
            return true;
        }

        return false;
    }

    /**
     * Check if a dialog has to be closed and render the js-output
     * Usage: see header comment
     *
     * @static
     * @param null $customJsScript
     * @return bool
     */
    public static function checkDialogJsScript($customJsScript=null)
    {
        if(!self::isDialogRequest())
            return false;

        $script = '';

        $closeOnAction = !empty($_GET[self::URLPARAM_CLOSEONACTION]) ? $_GET[self::URLPARAM_CLOSEONACTION] : false;
        if($closeOnAction)
        {
            $dialogId = !empty($_GET[self::URLPARAM_DIALOGID]) ? $_GET[self::URLPARAM_DIALOGID] : null;
            $gridId = !empty($_GET[self::URLPARAM_GRIDID]) ? $_GET[self::URLPARAM_GRIDID] : null;
            $iframeId = !empty($_GET[self::URLPARAM_IFRAMEID]) ? $_GET[self::URLPARAM_IFRAMEID] : null;

            if(!empty($dialogId))
                $script .= "window.parent.$('#$dialogId').dialog('close');";

            if(!empty($iframeId))
                $script .= "window.parent.$('#$iframeId').attr('src','');";
        }

        if(!empty($gridId))
            $script .= "window.parent.$.fn.yiiGridView.update('$gridId');";

        if(!empty($customJsScript))
            $script .= $customJsScript;

        if(!empty($script))
              echo CHtml::script($script);

        if($closeOnAction && !empty($dialogId))
        {
            Yii::app()->end();
        }

        die(htmlentities($script));

        return !empty($script);
    }
}
