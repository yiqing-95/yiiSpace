<?php

Yii::import('zii.widgets.CPortlet');

class EditorsPicks extends CPortlet
{
	public $maxPicks=5;
	
	public function getEditorsPicks()
	{
		return Post::model()->findEditorsPicks($this->maxPicks);
	}

	protected function renderContent()
	{
		$this->render('editorsPicks');
	}
}