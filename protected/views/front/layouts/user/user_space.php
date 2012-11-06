<?php $this->beginContent('//layouts/main'); ?>
<style type="text/css">
    .btn-toolbar .btn-group {
        display: inline-block;
    }
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

<div class="row userSpacer">
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
    </div>

    <div class="span7 userHeader">
        <div style="padding-left: 30px;">
            <h1>
                <?php echo $user->username; ?>
            </h1>

            <div><strong>3</strong> Projects</div>
            <div class="userBio">
                <strong>Bio</strong>

                <p class="userHeader">
                    Principal at @XtremeLabs, fronts @TheEatons. Handy with things #mobile, #agile, #startups. Loves
                    #music,
                    #design. Giant information junkie.
                </p>
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
    <div class="span10 offset2 userHeader">
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
                    array('label' => '相册', 'url' => '#'),
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

<hr size="2px">
<div class="row-fluid">
    <div class="span12">
        <!--        Fluid 12-->
        <?php
        if (!empty($this->menu)) :
            $this->widget("bootstrap.widgets.TbMenu", array('items' => $this->menu, 'type' => 'pills'));
        endif;
        ?>
        <div class="row-fluid">
            <div class="span9">
                <?php echo $content; ?>
            </div>
            <div class="span3" style="">
<!--                最近来访-->
                <h3>最近来访：</h3>
                <?php for ($j = 0; $j < 3; $j++): ?>
                <div class="row-fluid span12">
                    <ul class="thumbnails">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                        <li class="span3">
                            <a href="#" class="thumbnail">
                                <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                            </a>
                            <p>
                            </p>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </div>
                <?php endfor; ?>
                <!--                最近来访 end-->

                <div class="row-fluid span12">
                    <h3>-------</h3>
                    <?php $profile->renderSidebar(); ?>
                </div>


            </div>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>