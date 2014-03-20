<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-11
 * Time: 下午9:31
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
$alias = 'artDialog';
Yii::setPathOfAlias($alias,dirname(__FILE__));
Yii::import($alias.'.ArtDialog');

class ArtFormDialog extends ArtDialog{

    /**
     * 触发对话框弹出的css 链接选择器
     * @var
     */
    public $link;

    /**
     * 传递给底层对话框的参数选项
     * @var array
     */
    public $dialogOptions = array();

    /**
     * 提交成功后关闭对话框？
     * @var bool
     */
    public $closeOnSuccess = true ;

    public function init(){
        parent::init();
        $this->options['id'] = $this->getId();
        if(!isset($this->options['closeOnSuccess'])){
            $this->options['closeOnSuccess'] = $this->closeOnSuccess ;
        }
    }

    public function run(){
        parent::run();
        self::registerFormDialogPlugin();
        if(empty($this->link)){
            return ;
        }

        // 如果不是成功就关闭对话框 那么最好手动关闭它 在这个方法里面
        if (!isset($this->options['onSuccess']))
            $this->options['onSuccess']='js:function(data ,dialog , e){alert("Success")}';


        $this->options['dialogOptions'] = $this->dialogOptions ;

        $options= CJavaScript::encode($this->options);

        $jsCode = <<<INIT

 $(document).on('click',"{$this->link}", function (e) {
 // $("{$this->link}").on('click',function (e) {
                e.preventDefault();
             //   alert($(this).html());
                  $(this).formDialog({$options});
                return false;
            });


INIT;
        // 以前是在pos_head 段加的 ！！bug 纠正了
        Yii::app()->clientScript->registerScript('FormDialog'.$this->link, $jsCode,CClientScript::POS_READY);

    }

    public static  function registerFormDialogPlugin(){
        $js = <<<PLUGIN
;(function ($) {

  $.fn.formDialog = function (options) {

        return this.each(function(){
            var link = $(this);
            // 将options 存在该jquery对象上
            var url = link.attr('href');
            //alert(url);

            var artDialogId = options['id'];

            $.ajax({
                'url':url,
                'dataType': 'json',
                'success': function (data) {

                    var dialogContent = $('<div class="content-wrapper"> <div class="forView  "></div> </div> ');

                    dialogContent.find('.forView').html(data.view || data.form);

                    var defaultDialogOptions = {
                        id:artDialogId,
                        width: 460
                    }
                    var dialogOptions = $.extend(defaultDialogOptions,options["dialogOptions"]);
                    dialogOptions['content'] = dialogContent.html();

                    artDialogId =  $.dialog(dialogOptions);

                    $(".forView").delegate('form', 'submit', function (e) {
                        e.preventDefault();
                        $.ajax({
                            'url': link.attr('href'),
                            'type': 'post',
                            'data': $(this).serialize(),
                            'dataType': 'json',
                            'success': function (data) {
                                if (data.status == 'failure'){
                                    $('.forView').html(data.view || data.form);
                                }else if (data.status == 'success') {
                                    // var dialog = $.dialog.get(artDialogId);

                                    if(options['closeOnSuccess']==true){
                                        artDialogId.close();
                                    }
                                    options['onSuccess'](data,artDialogId , e);
                                }
                            }
                        });

                    });

                }
            });
        });
    };
    $.fn.formDialog.options = {};

})(jQuery);

PLUGIN;
      Yii::app()->clientScript->registerScript(__CLASS__.__METHOD__,$js,CClientScript::POS_HEAD);


    }

}