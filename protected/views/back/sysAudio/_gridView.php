<?php $this->widget('my.widgets.audio.JMiniAudioPlayer', array(
    // 'container' => '.slider-viewport',
    'debug'=>true ,
    'options' =>array(),
));

?>

    <style type="text/css">

        /*Generic page style*/


        .wrapper{
            position:absolute;
            padding:100px 50px;
            width:80%;
            min-height: 100%;
            margin-left: 10% ;
            top:0;
            background: #e8e8e8;

            font:normal 16px/20px Lekton, sans-serif;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.4);
        }


        .wrapper h1{
            font-family:Arial, Helvetica, sans-serif;
            font-size:26px;
        }

        button{
            padding:3px;
            display:inline-block;
            cursor:pointer;
            font:16px/18px Arial, Helvetica, sans-serif;
            color:#fff;
            background-color:#ccc;
            border-radius:5px;
            box-shadow:#999 1px 1px 3px;
            border:1px solid white;
            text-shadow: 1px -1px 2px #aaa9a9 !important;
        }

        button:hover{
            color:#666;
        }

        hr{
            border:none;
            background-color:#ccc;
            height:1px;
        }

        .wrapper span.param{
            font:normal 13px/15px Lekton, sans-serif;
            color:#767676;
            display: block;
            margin-top: 10px;
        }

    </style>

    <script type="text/javascript">

        $(function(){

            if (self.location.href == top.location.href){
                $("body").css({font:"normal 13px/16px 'trebuchet MS', verdana, sans-serif"});
                var logo=$("<a href='http://pupunzi.com'><img id='logo' border='0' src='http://pupunzi.com/images/logo.png' alt='mb.ideas.repository' style='display:none;'></a>").css({position:"absolute", top:10, left:10});
                $(".wrapper").prepend(logo);
                $("#logo").fadeIn();
            }
            var ua = navigator.userAgent.toLowerCase();
            var isAndroid = /android/.test(ua);
            var isAndroidDefault = isAndroid && !(/chrome/i).test(ua);

            if(isAndroidDefault){
                alert("Sorry, your browser does not support this implementation of the player. It will be used the standard HTML5 audio player instead")
            }

            $(".audio").mb_miniPlayer({
                width:240,
                inLine:false,
                id3:false,
                downloadPage:null
//                downloadPage:"map_download.php"
            });

        });

    </script>
<!--      以上纯拷贝！-->

<?php 
        $gridView  =  $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'sys-audio-items-view', // same as list view
            'ajaxUpdate'=>false , // 禁用掉ajax分页 不然音频比较麻烦
         'summaryCssClass'=>'pull-right',
        'pager'=> array('class'=>'my.widgets.TbMixPager'),
        //  使用下面的模板来做ajax延迟加载
        //'template' =>  Yii::app()->request->getIsAjaxRequest()? "{summary}{pager}\n{items}\n{pager}\n" : "",
        'template' =>   "{summary}{pager}\n{items}\n{pager}\n" ,
         'afterAjaxUpdate'=>'js:function(){ parent.risizeIframe();}',
        //  'dataProvider'=>$dataProvider, // do not use $model->search() if you want use pageSize widget
        // 使用下面的数据提供者配置做ajax延迟加载！
        //'dataProvider'=>Yii::app()->request->getIsAjaxRequest()? $dataProvider : new CArrayDataProvider(array()) , // 使用假数据提供者
        'dataProvider'=>  $dataProvider  ,
        'filter'=>$model,
        'columns'=>array(
        array(
        'class'=>'CCheckBoxColumn',
        'headerTemplate'=>'',// do not render the default checkAll checkBox
        'id'=>'items', // ids is used by AdminBulkActionForm
        'selectableRows'=>2, // must be greater than 2 to allow multiple row can be checked
        ),
		'id',
		'uid',
		'name',
		'singer',
		'summary',
            array(
              'name'=>'uri',
                'type'=>'raw',
                'value'=>function($data,$row){
                        //   <a id="m3" class="audio {skin:'blue', autoPlay:false,showRew:false}"
                        // href="http://www.miaowmusic.com/mp3/Miaow-08-Stirring-of-a-fool.mp3">miaowmusic - Stirring of a Fool (mp3)</a>
                        return  CHtml::link($data->uri,
                            YsUploadStorage::instance()->getUrl($data->uri,true),
                            array(
                                'id'=>'audio_'.$data->primaryKey,
                                'class'=>'audio {skin:"blue",autoPlay:false,showRew:false}'
                            )
                        ) ;
                    },
            ),
		// 'uri',
		/*
		'source_type',
		'play_order',
		'listens',
		'create_time',
		'cmt_count',
		'glean_count',
		'file_size',
		'status',
		*/
    array(
         'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
<?php  if(!Yii::app()->getRequest()->getIsAjaxRequest()): ?>
<script type="text/javascript">
   /*
    jQuery(function(){
        setTimeout(function(){
            $.fn.yiiGridView.update('sys-audio-items-view');
        },1500);
    });
    */
</script>
<?php  endif; ?>