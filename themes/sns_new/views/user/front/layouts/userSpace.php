<?php $this->beginContent('//layouts/main'); ?>

    <div class="container site-body ">

        <div class="col">
            <div class="cell">
                <div class="col">
                    <div class="col width-1of5 cell">
                        <div class="">
                            <img src="<?php echo UserHelper::getSpaceOwnerIconUrl(); ?>" width="64px" height="64px">
                        </div>
                        <div>
                            <?php
                            $topNavList = YsNavSystem::getUserSpaceNav('profile_nav');

                            foreach ($topNavList as $fromModule => $moduleMenuConfig):
                                foreach ($moduleMenuConfig as $menuKey => $menuConfig):
                                    ?>

                                    <li class="">
                                        <?php
                                           // 这里可以考虑 phpExpression的使用 配置全部来自string 动态变量可以执行之
                                           if(isset($menuConfig['class'])){
                                             $className =  Yii::import($menuConfig['class']);
                                               unset($menuConfig['class']);
                                               $options =  $menuConfig ;
                                              // print_r($options);
                                             if(is_subclass_of($className,'CWidget')){
                                                 $this->widget($className,$options);
                                             }
                                           }
                                           // print_r($menuConfig);
                                        ?>
                                    </li>

                                <?php
                                endforeach;
                            endforeach;
                            ?>
                        </div>

                    </div>
                    <div class="col width-3of5">
                        <div class="cell">
                            <div class="menu cell page-sub-menu  ">
                                <ul class="bottom nav">
                                    <?php
                                    $topNavList = YsNavSystem::getUserSpaceNav('top_nav');

                                    foreach ($topNavList as $fromModule => $moduleMenuConfig):
                                        foreach ($moduleMenuConfig as $menuKey => $menuConfig):
                                            ?>

                                            <li class="">
                                                <?php
                                                $menuConfig['url']['u'] = UserHelper::getSpaceOwnerId();
                                                echo CHtml::link($menuConfig['text'], $menuConfig['url']);
                                                ?>
                                            </li>

                                        <?php
                                        endforeach;
                                    endforeach;
                                    ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            <div class="cell menu">
                                <ul class="links nav">
                                    <?php if(!user()->getIsGuest()): ?>
                                        <li class="">
                                            <a href="<?php echo UserHelper::getUserSpaceUrl(Yii::app()->user->getId()); ?> ">我的空间</a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="container site-body">

        <div class="cell">

            <div class="col width-1of4">
                <?php $spaceOwnerModel = UserHelper::getSpaceOwnerModel();

                ?>

                <div class="cell">
                    <?php YsPageBox::beginPanel() ; ?>
                     <div class="col">
                            <div class="cell " >
                                <figure class="nuremberg">
                                    <img src="<?php echo $spaceOwnerModel->getIconUrl(); ?>" alt="" width="100px" height="100px">
                                    <figcaption>Efteling</figcaption>
                                </figure>
                            </div>
                         <div class="cell">
                               <ul class="nav">
                                   <li>
                                       <?php echo $spaceOwnerModel->getAttributeLabel('usernam') ?>:
                                       <?php echo CHtml::encode($spaceOwnerModel->username); ?>
                                   </li>
                                   <li>
                                       注册时间：<?php echo Yii::app()->dateFormatter->format('y-m-d',$spaceOwnerModel->create_at); ?>
                                   </li>
                                   <li>
                                       <a class="button user-op-following"
                                          data-object-id="<?php echo $spaceOwnerModel->primaryKey ?>"
                                           style="display: none"
                                           >
                                           关注
                                       </a>
                                       <a class="button user-cate-following"
                                          data-object-id="<?php echo $spaceOwnerModel->primaryKey ?>"
                                           href="<?php echo Yii::app()->createUrl('/friend/relationshipCategory/create',
                                                     array('userId'=>$spaceOwnerModel->primaryKey)
                                           ); ?>"
                                           >
                                           关注
                                       </a>
                                       <?php
                                       $onCategoryCreateSuccess = <<<CB
                function(data, dialog , e){
                    var category = data.category;
                    var newOption = "<option value='"+category.id+"'>"+category.name+"</option>";

                    $("#friend_category_id").prepend(newOption).val(category.id);
                    // 存储对话框引用到按钮
                     friendArtDialog = dialog;
                     // $("#confirm_friend_category_selection").data("dialog",dialog);
                     //alert(data.message);
                    // $.alert(data.message);
                }
CB;
                                       $this->widget('my.widgets.artDialog.ArtFormDialog', array(
                                               'id'=>'user_add_friend_dialog',
                                               'link' => 'a.user-cate-following',
                                               'options' => array(
                                                   'onSuccess' => 'js:' . $onCategoryCreateSuccess,
                                                   'closeOnSuccess'=>false ,
                                               ),
                                               'dialogOptions' => array(
                                                   'title' => '选择用户组',
                                                   'width' => 500,
                                                   'height' => 370,

                                               )
                                           )
                                       );
                                       ?>

                                       <script type="text/javascript">
                                           // 全局变量 用来通讯的
                                           var friendArtDialog = null ;
                                           $(function(){
                                               var getHasFollowed = function(){
                                                   var url = "<?php echo  $this->createUrl('/friend/relationship/hasFollowed'); ?>";
                                                   var $opElement = $("a.user-op-following");
                                                   var params = {
                                                       objectId: $opElement.attr("data-object-id")
                                                   };
                                                   $.getJSON(url,params,function( resp){
                                                       if(resp.status == 'success'){
                                                           if(! resp.hasFollowed){
                                                               $opElement.css('display','block');
                                                           }
                                                       }else{

                                                       }
                                                   });
                                               };
                                               // 延迟些时间在判断是不是已经follow了 ie有个settimeout最小时间！
                                               setTimeout(getHasFollowed,13);

                                               // 分组选择对话框中的确定按钮点击事件处理器
                                               $("body").on('click','#confirm_friend_category_selection',function(){
                                                   var category = $("#friend_category_id").val();
                                                  if(category == ''){
                                                      alert('请创建用户分组！');
                                                      return  ;
                                                  }
                                                   var userId = $("#friend_object_user_id").val();

                                                   var url = "<?php echo  $this->createUrl('/friend/relationship/follow'); ?>";

                                                   var params = {
                                                       objectId: userId,
                                                       categoryId: category
                                                   };
                                                   $.post(url,params,function( resp){
                                                       resp = $.parseJSON(resp);
                                                       if(resp.status == 'success'){
                                                           alert("添加成功！");
                                                           // 关闭对话框
                                                           friendArtDialog.close();
                                                       }else{

                                                       }
                                                   });

                                               });

                                               $("body").on('click','a.user-op-following',function(){

                                                   var url = "<?php echo  $this->createUrl('/friend/relationship/follow'); ?>";
                                                   var $opElement = $("a.user-op-following");
                                                   var params = {
                                                       objectId: $opElement.attr("data-object-id")
                                                   };
                                                   $.post(url,params,function( resp){
                                                       alert(resp);
                                                       if(resp.status == 'success'){

                                                       }else{

                                                       }
                                                   });

                                                   return false ;
                                               }) ;
                                           });
                                       </script>
                                   </li>
                               </ul>
                         </div>

                     </div>
                    <?php YsPageBox::endPanel() ; ?>

                    <?php  YsPageBox::beginPanel(array('template'=>'{header}{body}','header'=>'最近访客') ); ?>
                    <div class="cell">
                        <?php  $this->widget('user.widgets.4cascadeFr.latestVisitors.LatestVisitors', array(
                            'spaceId' => $spaceOwnerModel->primaryKey,
                            'maxCount' => 9,
                        ));  ?>

                    </div>
                    <?php  YsPageBox::endPanel() ;?>

                    <?php  YsPageBox::beginPanel(array('template'=>'{header}{body}','header'=>'最近空间访问统计') ); ?>
                    <div class="cell">
                        <?php

                        $this->widget('user.widgets.4cascadeFr.SpaceVisitStatBox', array(
                            'spaceOwnerId' => $spaceOwnerModel->primaryKey,
                        ));

                        ?>

                    </div>
                    <?php  YsPageBox::endPanel() ;?>
                </div>

            </div>

            <div class="col width-fill">
                <?php echo $content; ?>
            </div>

        </div>

    </div>
<?php $this->endContent(); ?>