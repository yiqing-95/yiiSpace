Обёртка для плагина jQuery Uploadify
====================================

Используем в представлении `views/form.php`:
~~~
[php]
$this->widget('ext.yiiext.widgets.uploadify.EUploadifyWidget', array(
	// можно использовать как для поля модели
	'model'=>new UploadifyFile,
	'attribute'=>'uploadifyFile',
	// так и просто для элемента формы
	'name'=>'my_input_name',
	// Имя POST-параметра, через который будет посылаться ИД сессии
	'sessionParam'=>'PHP_SESSION_ID',
	// [настройки](http://www.uploadify.com/documentation/) плагина
	'options'=>array(
		'fileExt'=>'*.jpg;*.png;*.gif',
		'script'=>$this->createUrl('controller/action'),
		'auto'=>false,
		'multi'=>true,
		'buttonText'=>'Upload Images',
	),
));
~~~

Использование бета-версии 3.0.0
~~~
[php]
$this->widget('ext.yiiext.widgets.uploadify.EUploadifyWidget',array(
	'package'=>array(
		'basePath'=>Yii::getPathOfAlias('ext.yiiext.widgets.uploadify').'/vendors/jquery.uploadify-v3.0.0',
		'js'=>array(
			'jquery.uploadify'.(YII_DEBUG?'':'.min').'.js',
			'swfobject.js',
		),
		'css'=>array(
			'uploadify.css',
		),
		'depends'=>array(
			'jquery',
		),
	),
));
~~~

Пример использования
--------------------

Для использования нам нужны:

- Модель формы.
- Действие контроллера.

### Пример модели

`UploadifyFile.php`:
~~~
[php]
class UploadifyFile extends CFormModel
{
	public $uploadifyFile;

	public function rules()
	{
		return array(
			array(
				'uploadifyFile',
				'file',
				'maxSize'=>1024*1024*1024,
				'types'=>'jpg, png, gif, txt',
			),
		);
	}
}
~~~

### Пример действия

Действие можно описать и как обычное действие контроллера, но здесь мы используем [CAction, описанный в полном руководстве](http://yiiframework.ru/doc/guide/ru/basics.controller).

~~~
[php]
class SwfUploadAction extends CAction
{
	public $folder;

	public function run()
	{
		$folder=$this->folder;
		if($folder===FALSE)
		{
			throw new CException(Yii::t(__CLASS__,"Folder does not exists.",array()));
		}
		if(isset($_FILES['UploadifyFile'])===true)
		{
			$model=new UploadifyFile;
			$model->attributes=array('uploadifyFile'=>'');
			$model->uploadifyFile=CUploadedFile::getInstance($model,'uploadifyFile');
			if($model->validate()===false)
			{
				throw new CException(Yii::t(__CLASS__,"Invalid file.",array()));
			}
			if(!$model->uploadifyFile->saveAs($folder.'/'.$model->uploadifyFile->getName()))
			{
				throw new CException(Yii::t(__CLASS__,"Upload error.",array()));
			}
			else
			{
				die("Upload success");
			}
		}
		else
		{
			throw new CException(Yii::t(__CLASS__,"File not sent.",array()));
		}
		throw new CException(Yii::t(__CLASS__,'Unknown error.',array()));
	}
}
~~~

*Вы также можете использовать [EFileUploadAction] из yiiext.*