<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Oauth <small>1.0a 版本。</small></h1>

<hr/>
<h2>文档 Document</h2>
<a href="http://oauth.net/" class="btn info ">Oauth 官方</a>
<a href="http://open.weibo.com/wiki/Oauth" class="btn ">新浪 Oauth 解释</a>


<hr/>
<h2>客户端 Client</h2>
<p>
这是一个简易的客户端，提供两种方式web和app 登录。 <?php echo CHtml::link('客户端',array('client/index'),array('class'=>'btn danger'))?>

</p>

<hr/>
<h2>服务端 Service</h2>


<table class="wiki_table" border="0" cellspacing="0" cellpadding="0">
<tbody><tr>
<th class="wiki_table_thfirst"> 接口
</th><th> 说明
</th></tr>
<tr>
<td class="wiki_table_tdfirst"><a href="<?php echo Yii::app()->createAbsoluteUrl('api/oauth/authorize');?>" title="Oauth/authorize">oauth/authorize</a>
</td><td>请求用户授权Token
</td></tr>
<tr>
<td class="wiki_table_tdfirst"><a href="<?php echo Yii::app()->createAbsoluteUrl('api/oauth/request_token');?>" title="OAuth/request token">oauth/access_token</a>
</td><td>获取授权过的Request Token
</td></tr>
<tr>
<td class="wiki_table_tdfirst"><a href="<?php echo Yii::app()->createAbsoluteUrl('api/oauth/access_token');?>" title="OAuth/access token">oauth/access_token</a>
</td><td>获取授权过的Access Token
</td></tr>
</tbody></table>
	
