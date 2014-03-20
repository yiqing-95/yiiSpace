<h1>My Relationships</h1>


<div class="cell">
    <div class="col">
        <div class="col width-7of9 tablet-width-1of2">
            adfsgfsdfg
            <?php $this->widget('zii.widgets.CListView', array(
                'id' => 'relationship-list',
                'dataProvider' => $dataProvider,
                'template' => '{summary}{sorter}{items}{pager}',
                'itemsTagName' => 'ul',
                'itemsCssClass' => 'nav',
                'htmlOptions' => array(
                    'class' => 'gallery cell',
                ),
                'itemView' => 'follower/_myRelationshipsView',
            )); ?>
        </div>
        <div class="col width-fill tablet-width-fill ">
            <div class="cell">
                <div class="menu cell">
                    <ul class="left nav">
                        <li>
                            <?php
                            echo CHtml::link('找朋友',
                                Yii::app()->controller->createUrl('relationship/followerList',
                                    array('u' => UserHelper::getSpaceOwnerId()))
                            );
                            ?>
                        </li>
                        <li>
                            <?php
                            echo CHtml::link('追随者',
                                Yii::app()->controller->createUrl('relationship/followerList',
                                    array('u' => UserHelper::getSpaceOwnerId()))
                            );
                            ?>
                        </li>
                    </ul>
                </div>

                <?php
                $this->widget('friend.widgets.FriendCategoryWidget', array(
                    'userId' => UserHelper::getSpaceOwnerId(),
                ));
                ?>

            </div>
        </div>
    </div>
</div>


<?php
$this->widget('my.widgets.artDialog2.ArtDialog2');
?>

<script type="text/javascript">



    seajs.use(['jquery', 'artDialog'], function ($, dialog) {
        $(function () {

            var d;
            var cnt = 0 ;
            var lock = false ;
           // $(".user-simple-box").mouseover(
            $("body").on( "mouseover",".user-simple-box",
                function () {
                    if(d !== undefined){
                        d.close();
                    }

                    if(lock == true){
                        return ;
                    }

                    var that = this ;

                    var url = $(this).attr('data-profile-box-url');
                       // make sure only one ajax request is made
                       lock = true ;
                        $.get(url,function(resp){
                            /*
                            d = dialog({
                                content:"<div class='' style='width: 10%'>"+ resp+"<div>"
                                // ,quickClose: true// 点击空白处快速关闭
                                 ,width : '250'
                                ,skin: 'min-dialog tips'
                                ,cancel: function () {}
                            });
                            d.show($(that).get(0));
                            */

                        d = dialog({
                              // id: 'align-test',

                                title: '  ',//'消息',
                                content: "<div class='' style='width: 10%'>"+ resp+"<div>",
                                width : '250',
                                skin: 'min-dialog tips',
                                okValue: '确 定',
                                align: $(this).data('align'),
                                //height: 1024,
                              //  ok: function () {},
                                // cancelValue: '取消',
                                // cancel: function () {}
                            });
                            d.show($(that).get(0));
                            // unlock !
                            lock = false ;

                        });
                    cnt++ ;
                    $("#msg").html(""+cnt);

                });
           /*
           $('body').click(function(){
               if(d !== undefined){
                   d.close();
               }
            });
            */

        });
    });

</script>
<div id="msg"> </div>
