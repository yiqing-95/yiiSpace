<h1>My Relationships</h1>


<div class="cell">
    <div class="col">
        <div class="col width-7of9 tablet-width-1of2">
            adfsgfsdfg
            <?php $this->widget('zii.widgets.CListView', array(
                'id' => 'relationship-list',
                'dataProvider' => $dataProvider,
                'template' => '{items}',
                'itemsTagName' => 'ul',
                'itemsCssClass' => 'nav',
                'htmlOptions' => array(
                    'class' => 'gallery cell',
                ),
                'itemView' => 'member/_memberView',
            )); ?>
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
                            d = dialog({
                                content:"<div class='' style='width: 10%'>"+ resp+"<div>"
                                // ,quickClose: true// 点击空白处快速关闭
                                 ,width : '250'
                                ,skin: 'min-dialog tips'
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
