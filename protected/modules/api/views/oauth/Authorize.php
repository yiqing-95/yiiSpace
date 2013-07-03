<section id="about">

        <?php if(!empty($errmsg)):?>

            <?php echo $errmsg;?>
        <?php endif;?>
  <div class="page-header">
    <h1>授权<small><?php echo $rs['application_title']?></a> &nbsp;访问你的账号,享受更便捷服务</small></h1>
  </div>
  <div class="row">
    <div class="span-one-third">
      <h3>某某应用</h3>
        <p> 开发者：m1840<br>
        共 <span class="gray6">3</span> 人使用该应用 </p>
      <ul>
        <li>获得你的个人信息，好友关系</li>
        <li>分享内容到社区</li>
        <li>获得你的评论</li>
      </ul>
        <p>你可以随时在 <a target="_blank " href="#">我的设置</a> 里取消授权。</p>
    </div>
    <div class="span-two-thirds">
      <h3>登录授权</h3>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Hint: You may login with <tt>demo/demo</tt>.
		</p>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('登录并授权'); ?>
	</div>

<?php $this->endWidget(); ?>
    </div>
  </div><!-- /row -->

</section>

