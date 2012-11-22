<?php $this->beginContent('//layouts/main'); ?>
<style type="text/css">
    .btn-toolbar .btn-group {
        display: inline-block;
    }
    /*
    .row.content{
        border-radius: 10px 10px 10px 10px;
        background: none repeat scroll 0 0 white;
    }
    */
</style>
<!--    如果未登录-->
<?php
if (Yii::app()->user->getIsGuest()):
    ?>
<?php /** @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'inlineForm',
        'type' => 'inline',
        'htmlOptions' => array('class' => 'pull-right'),
    )); ?>

<?php //echo $form->textFieldRow($model, 'textField', array('class'=>'input-small')); ?>
<?php //echo $form->passwordFieldRow($model, 'password', array('class'=>'input-small')); ?>
<label>用户名</label>
<?php echo CHtml::textField('username', '', array('class' => 'input-small')) ?>
<label>密 码</label>
<?php echo CHtml::passwordField('password', '', array('class' => 'input-small')) ?>

<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Log in')); ?>

<?php $this->endWidget(); ?>
<?php endif; ?>

<!--如果未登录 end-->

<div class="row-fluid userSpacer">
    <div class="span2">
        <?php

        $user = new User();
        $profile = $this->widget('user.widgets.profile.UserProfile', array(
            'user' => $_GET['u'], //we assume when access some one 's space we will always pass the param "u" to the $_GET
        ));
        $user = $profile->getUserModel();
        ?>
        <p>
            <?php
            /*
           $this->widget('bootstrap.widgets.TbButtonGroup', array(
               'type' => 'primary',
               'toggle' => 'radio',
               'buttons' => array(
                   array('label'=>'关注'),
                   array('label'=>'取消关注'),
               ),
           ));*/

            $this->widget('bootstrap.widgets.TbToggleButton', array(
                'name' => 'testToggleButtonB',
                'enabledLabel' => '关注',
                'disabledLabel' => '取消关注',
                'value' => true,
                'width' => 150,
                'enabledStyle' => null,
                'customEnabledStyle' => array(
                    'background' => '#FF00FF',
                    'gradient' => '#D300D3',
                    'color' => '#FFFFFF'
                ),
                'customDisabledStyle' => array(
                    'background' => "#FFAA00",
                    'gradient' => "#DD9900",
                    'color' => "#333333"
                )
            ));
            ?>
        </p>
            <ul class="unstyled">
                <li><i class="icon-user"></i>加入于
                    <span class="badge badge-info">2012-11-11</span>
                </li>
                <li><i class="icon-star"></i>最后登录
                    <span class="badge badge-info">2012-11-11</span>
                </li>
            </ul>
    </div>
    <div class="span10">

        <div class="row-fluid">
            <div class="span8 userHeader">
                <div style="padding-left: 30px;">
                    <h1>
                        <?php echo $user->username; ?>
                    </h1>

                    <div>
                        <strong></strong>
                        <!--                Projects-->
                    </div>
                    <div class="userBio">
                        <strong>介绍</strong>

                        <p class="userHeader">
                            出生于天朝
                        </p>
                    </div>
                    <div class="user-tags">
                        <ul class="unstyled">
                            <li>屌丝</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="span3">
                <h2 style="margin-top: 50px;"></h2>

                <p>
                <ul class="unstyled">
                    <li><i class="icon-user"></i>关注
                        <span class="badge badge-info">23</span>
                    </li>
                    <li><i class="icon-star"></i>粉丝
                        <span class="badge badge-info">23</span>
                    </li>

                    <li><i class="icon-share"></i>状态
                        <span class="badge badge-info">23</span>
                    </li>
                </ul>
                </p>

                <?php /*
        <p>
        <dl class="dl-horizontal">
            <dt><i class="icon-user"></i>关注</dt>
            <dd>23</dd>

            <dt><i class="icon-star"></i>粉丝</dt>
            <dd>23</dd>

            <dt><i class="icon-share"></i>状态</dt>
            <dd>23</dd>
        </dl>
        </p>
       */ ?>
            </div>
        </div>
        <div class="row userSpacer">
            <!--    用户菜单（顶级）-->
            <div class="span10  userHeader pull-right ">
                <h2>
                    <!--            用户操作 菜单这里-->
                </h2>

                <div class="well well-small btn-toolbar " align="center">
                    <?php
                    $this->widget('bootstrap.widgets.TbButtonGroup', array(
                        'size' => 'large',
                        'htmlOptions' => array(
                            //  'class'=>'pull-right',
                        ),
                        'buttons' => array(
                            array('label' => '日志', 'url' => '#'),
                           // array('label' => '相册', 'url' => array('/album/member','u'=>$_GET['u'])),
                            array('label' => '相册', 'url' => array('/album/member')),
                            array('label' => '微博', 'url' => '#'),
                            array('label' => '收藏', 'url' => '#'),
                            array('label' => '分享', 'url' => '#'),
                        ),
                    ));
                    $this->widget('bootstrap.widgets.TbButtonGroup', array(
                        'size' => 'large',
                        'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'buttons' => array(
                            array('label' => 'Inverse', 'items' => array(
                                array('label' => 'Action', 'url' => '#'),
                                array('label' => 'Another action', 'url' => '#'),
                                array('label' => 'Something else', 'url' => '#'),
                                '---',
                                array('label' => 'Separate link', 'url' => '#'),
                            )),
                        ),
                    ));
                    ?>
                </div>

                <br/>
                <?php
                /*
               $this->widget('bootstrap.widgets.TbNavbar', array(
                   'fixed' => false,
                   'brand' => 'Title',
                   'items' => array(
                       array(
                           'class' => 'bootstrap.widgets.TbMenu',
                           'items' => array(
                               array('label' => 'Home', 'url' => '#', 'active' => true),
                               array('label' => 'Link', 'url' => '#'),
                               array('label' => 'Dropdown', 'items' => array(
                                   array('label' => 'Item1', 'url' => '#')
                               )),)
                       )
                   )
               )); */
                ?>
            </div>
            <!--    用户菜单（顶级）end -->
        </div>
    </div>




</div>



<div class="row content">
    <?php echo $content ;?>
</div>



<?php $this->endContent(); ?>