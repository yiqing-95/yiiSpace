<?php
    /**
     * EJuiDlgsColumn overrides the Yii CButtonColumn.
     *
     * By default, it will display three buttons, "view", "update" and "delete"
     *
     * Usage:
     *
    //Members column - see: http://www.yiiframework.com/wiki/278/cgridview-render-customized-complex-datacolumns/

     $this->widget('zii.widgets.grid.CGridView', array(
                                'id'=>'group-grid',
                                'dataProvider'=>$model->search(),
                                'columns'=>array(
                                        'id',
                                        'name',
                                          ....
                                        ),

                                array(
                                   'class'=>'EJuiDlgsColumn',

                                   //configure like CButtonColumns:
                                   //but some properties (buttons.view.url,buttons.view.id, buttons.view.ajax, buttons.update.click  ...) will be set by the component

                                   //'template'=>'{view}',
                                   'viewButtonImageUrl'=>Yii::Yii::app()->baseUrl .'images/dialogview.png',
                                   'buttons'=>array(
                                        'view' => array(
                                            'label'=> 'ajax dialog view',
                                        ),

                                        'delete' => array(
                                                            'label'=> 'someLabel',
                                                            ),
                                        ),

                                    //additional config for the dialogs starts here:

                                    //if you want to use a custom dialog config: default is  'ext.quickdlgs.juimodal'
                                    //'viewDialogConfig' => 'ext.quickdlgs.mycustomjuiattributes'

                                   //don't override the CButtonColumn view button
                                   //'viewDialogEnabled' = false,

                                    //the attributes for the EAjaxJuiDlg widget: use like the 'attributes' param from EQuickDlgs::ajaxButton above
                                    'viewDialog'=>array(
                                         //'controllerRoute' => 'view', //=default
                                         //'actionParams' => array('id' => '$data->primaryKey'), //=default
                                         'dialogTitle' => 'View detail',
                                         //'dialogWidth' => 800, //use the value from the dialog config
                                         //'dialogHeight' => 600,
                                    ),

                                    //the attributes for the EFrameJuiDlg widget. use like the 'attributes' param from EQuickDlgs::iframeButton
                                    'updateDialog'=>array(
                                           //'controllerRoute' => 'update', //=default
                                           //'actionParams' => array('id' => '$data->primaryKey'), //=default
                                           'dialogTitle' => 'View detail',
                                           'dialogWidth' => 1024, //override the value from the dialog config
                                           'dialogHeight' => 600,
                                        ),
                                  ),
                                ),
     *
     * The 'view' and 'update' buttons will open a CJuiDialog:
     *
     * By default
     * 1. view: with ajax content from controllerid/view
     * ------------------------------------------------------------
     *
     * The implementation follows the wiki article
     * @link http://www.yiiframework.com/wiki/262/cgridview-display-the-full-record-actionview-in-a-cjuidialog/
     *
     * Change the actionView of your controller, so that not the whole viewpage is rendered inside the dialog:
     * Use the EQuickDlgs::renderPartial to render the view-file partial if displayed in the ajax-dialog
     *
            public function actionView($id)
            {
                EQuickDlgs::render('view',array('model'=>$this->loadModel($id)));
                //$this->render('view',array('model'=>$this->loadModel($id)));
            }
     *
     * Set viewDialogEnabled=false to install the default viewButton of the CButtonColumn
     *
     *
     * 2. update: a iframe dialog to update the record
     * -----------------------------------------------------------
     *
     * The implementation follows the wiki article
     * @link http://www.yiiframework.com/wiki/263/cgridview-update-create-records-in-a-cjuidialog/
     *
     * Necessary changes to the actionUpdate: use EQuickDlgs::checkDialogJsScript and
     * replace the controller->render() with EQuickDlgs::render
     *
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
     *
     * Set updateDialogEnabled=false to install the default updateButton of the CButtonColumn
     *
     * The delete action is the default of the CButtonColumn.
     * For more features you can use this component and setting properties (template ...) like the CButtonColumn.
     *
     * @author Joe Blocher <yii@myticket.at>
     * @copyright 2012 myticket it-solutions gmbh
     * @license New BSD License
     * @version 1.2
     * @package ext.quickdlgs
     * @since 1.0
     */

    Yii::import('zii.widgets.grid.CGridColumn');

    class EJuiDlgsColumn extends CButtonColumn
    {
        /**
         * Set to false to enable the default 'view' action of the CButtonColumn
         * @var bool
         */
        public $viewDialogEnabled = true;

        /**
         * The standard attributes of the CJuiDialog loaded from ext.quickdlgs.config.juimodal.php
         * Copy or edit this file to modifiy the look and feel of the dialog (theme, themeurl, cssFile ...)
         * @var string
         */
        public $viewDialogConfig = EQuickDlgs::STDDIALOGATTRIBUTES;

        /*
         * The viewDialog will be rendered as EAjaxJuiDlg
         * These are the attributes of the EAjaxJuiDlg widget.
         * This array will be merged with the attributes from the viewDialogConfig above.
         * The attributes id,url and renderOpenButton will be set in the method renderViewDialogWidget
         *
         * If the controllerRoute is not assigned:
         * The value this will be set to 'view' and the actionParams will be set to array('id' => '$data->primaryKey')
         */
        public $viewDialog = array();

        /**
         * Set to false to enable the default 'view' action of the CButtonColumn
         * @var bool
         */
        public $updateDialogEnabled = true;

        /**
         * The standard attributes of the CJuiDialog loaded from ext.quickdlgs.config.juimodal.php
         * Copy or edit this file to modifiy the look and feel of the dialog (theme, themeurl, cssFile ...)
         * @var string
         */
        public $updateDialogConfig = EQuickDlgs::STDDIALOGATTRIBUTES;

        /*
         * The updateDialog will be rendered as EFrameJuiDlg
         * These are the attributes of the EFrameJuiDlg widget.
         * This array will be merged with the attributes from the viewDialogConfig above.
         * The attributes id,url and renderOpenButton will be set in the method renderViewDialogWidget
         *
         * If the controllerRoute is not assigned:
         * The value this will be set to 'view' and the actionParams will be set to array('id' => '$data->primaryKey')
         */
        public $updateDialog = array();

        /**
         * Get the controller route for the viewButton
         * Add $this->grid->controller->id, if not a full route is specified
         *
         * @return string
         */
        public function getViewControllerRoute()
        {
            if(!empty($this->viewDialog['controllerRoute']))
            {
                $route =  $this->viewDialog['controllerRoute'];
                if (strpos($route, '/') === false)
                    $route = $this->grid->controller->id . '/' . $route;
            }
            else
                $route =  $this->grid->controller->id;

            return $route;
        }

        /**
        * Get the controller route for the updateButton
        * Add $this->grid->controller->id, if not a full route is specified
        *
        * @return string
        */
        public function getUpdateControllerRoute()
        {
            if(!empty($this->updateDialog['controllerRoute']))
            {
                $route =  $this->updateDialog['controllerRoute'];
                if (strpos($route, '/') === false)
                    $route = $this->grid->controller->id . '/' . $route;
            }
            else
                $route =  $this->grid->controller->id;

            return $route;
        }

        /**
         * Return true if not assigned
         * @return bool
         */
        public function getHideTitleBar($type)
        {
            $item = $type.'Dialog';
            $attributes = $this->$item;

            return isset($attributes['hideTitleBar']) ? $attributes['hideTitleBar'] : false;
        }

        /**
         * Initialize the column.
         * Override the view and update buttons
         */
        public function init()
        {
            if($this->viewDialogEnabled)
            {
                $this->initViewButton();
                $this->renderViewDialogWidget();
            }

            if($this->updateDialogEnabled)
            {
                $this->initUpdateButton();
                $this->renderUpdateDialogWidget();
            }

            parent::init();
        }

        /**
         * Initialize the updateButton
         */
        protected function initUpdateButton()
        {
            if (!isset($this->updateDialog['controllerRoute']))
            {
                $this->updateDialog['controllerRoute'] = 'update';
                $this->updateDialog['actionParams'] = array('id' => '$data->primaryKey');
            }

            if (!isset($this->updateDialog['closeOnAction']))
                $this->updateDialog['closeOnAction'] = true;


            $updateButton = !empty($this->buttons['update']) ? $this->buttons['update'] : array();

            $iframeId = $this->getFrameId();
            $dialogId = $this->getDialogId('update');
            $contentWrapperId = $this->getDialogContentWrapperId('update');

            $click = 'function(){$("#' . $iframeId . '").attr("src",$(this).attr("href"));$("#' . $dialogId . '").dialog("open");';

            //check hide the siblings div with class 'ui-dialog-titlebar'
            if($this->getHideTitleBar('update'))
                $click .= "$('.$dialogId div.ui-dialog-titlebar').hide();";

            $click .= 'return false;}';

            $this->buttons['update'] = CMap::mergeArray(
                $updateButton,
                array(
                    'url' => $this->getUpdateButtonUrl(),
                    'click' => $click,
                ));
        }

        /**
         * Initialize the viewButton
         */
        protected function initViewButton()
        {
            if (!isset($this->viewDialog['controllerRoute']))
            {
                $this->viewDialog['controllerRoute'] = 'view';
                $this->viewDialog['actionParams'] = array('id' => '$data->primaryKey');
            }

            if (!isset($this->viewDialog['closeOnAction']))
                $this->viewDialog['closeOnAction'] = true;

            $viewButton = !empty($this->buttons['view']) ? $this->buttons['view'] : array();

            $this->buttons['view'] = CMap::mergeArray(
                $viewButton,
                array(
                    'url' => $this->getViewButtonUrl(),
                    'options' => array(
                        'ajax' => array(
                            'type' => 'POST',
                            // ajax post will use 'url' specified above
                            'url' => "js:$(this).attr('href')",
                            'update' => '#' . $this->getDialogContentWrapperId('view'),
                        ),
                    ),
                ));
        }

        /**
         * Render the code for the view-CJuidialog
         * Modify or copy the ext.config.juimodal.php to change the look and feel of the CJuiDialog
         */
        protected function renderViewDialogWidget()
        {
            $widgetAttributes = EQuickDlgs::getDialogWidgetAttributes($this->viewDialog,$this->viewDialogConfig);

            $widgetAttributes['id'] = $this->getDialogWidgetId('view');
            $widgetAttributes['controllerRoute'] = $this->getViewControllerRoute('view');
            $widgetAttributes['actionParams'] = array();  //reset for the widget: was used to create the button url;
            $widgetAttributes['renderOpenButton'] = false;
            $widgetAttributes['url'] = null;

            EQuickDlgs::renderDialogWidget($widgetAttributes,EQuickDlgs::TYPEAJAX);
        }

        /**
         * Render the code for the update-CJuidialog
         * Modify or copy the ext.config.juimodal.php to change the look and feel of the CJuiDialog
         */
        protected function renderUpdateDialogWidget()
        {
            $widgetAttributes = EQuickDlgs::getDialogWidgetAttributes($this->updateDialog,$this->viewDialogConfig);

            $widgetAttributes['id'] = $this->getDialogWidgetId('update');
            $widgetAttributes['controllerRoute'] = $this->getUpdateControllerRoute('view');
            $widgetAttributes['actionParams'] = array();  //reset for the widget: was used to create the button url;
            $widgetAttributes['renderOpenButton'] = false;
            $widgetAttributes['url'] = null;

            EQuickDlgs::renderDialogWidget($widgetAttributes,EQuickDlgs::TYPEIFRAME);
        }

        /**
         * Get the dialog id for the htmlOptions
         * @param $action
         * @return string
         */
        public function getDialogId($action)
        {
            return $this->getDialogWidgetId($action) . '-dlg';
        }

        /**
         * Get the id for the widget
         * @param $action
         * @return string
         */
        public function getDialogWidgetId($action)
        {
            return $this->id . $action . 'Wdgt';
        }

        /**
         * Get the id for content div inside the dialog
         * @param $action
         * @return string
         */
        public function getDialogContentWrapperId($action)
        {
            return $this->getDialogWidgetId($action) . '-content';
        }

        /**
         * Get the id of the iframe-tag inside the update dialog
         * @return string
         */
        protected function getFrameId()
        {
            return $this->getDialogWidgetId('update') . '-frame';
        }

        /**
         * Create the url for the buttons
         * Add the necessary GET-params
         *
         * @param $route
         * @param $paramStr
         * @param $isIFrame
         * @param $isUpdate
         * @return string
         */
        protected function createButtonUrl($route,$paramStr,$isIFrame,$isUpdate)
        {
            $url = "Yii::app()->createUrl('$route',array({$paramStr})).";
            $params = array(
                EQuickDlgs::URLPARAM_CLASS => get_class($this),
                EQuickDlgs::URLPARAM_DIALOGID => $isUpdate ? $this->getDialogId('update') : $this->getDialogId('view'),
                EQuickDlgs::URLPARAM_GRIDID => $this->grid->id,
                EQuickDlgs::URLPARAM_CONTENTWRAPPERID => $isUpdate ? $this->getDialogContentWrapperId('update') : $this->getDialogContentWrapperId('view'),
            );

            if($isIFrame)
               $params[EQuickDlgs::URLPARAM_IFRAMEID]=$this->getFrameId();

            if($isUpdate && isset($this->updateDialog['closeOnAction']))
                $params[EQuickDlgs::URLPARAM_CLOSEONACTION]=$this->updateDialog['closeOnAction'];

            $method = $isUpdate ? 'update' : 'view';
            if($this->getHideTitleBar($method))
                $params[EQuickDlgs::URLPARAM_HIDETITLEBAR]=1;

            $url .= '"?' . http_build_query($params) . '"';

            return $url;
        }


        /**
         * Get the url for the viewButton
         * @return string
         */
        protected function getViewButtonUrl()
        {
           $paramStr = '';
           $actionParams = isset($this->viewDialog['actionParams']) ? $this->viewDialog['actionParams'] : array();

           foreach($actionParams as $key=>$value)
           {
               $paramStr .= '"'.$key.'"=>'.$value .',';
           }

           if(!empty($paramStr))
               $paramStr = substr($paramStr,0,-1);

           return $this->createButtonUrl($this->getViewControllerRoute(),$paramStr,false,false);
        }


        /**
         * Get the url for the updateButton
         * @return string
         */
        protected function getUpdateButtonUrl()
        {
            $paramStr = '';

            $actionParams = isset($this->updateDialog['actionParams']) ? $this->updateDialog['actionParams'] : array();

            foreach($actionParams as $key=>$value)
            {
                $paramStr .= '"'.$key.'"=>'.$value .',';
            }

            if(!empty($paramStr))
                $paramStr = substr($paramStr,0,-1);

            return $this->createButtonUrl($this->getUpdateControllerRoute(),$paramStr,true,true);
        }
    }
