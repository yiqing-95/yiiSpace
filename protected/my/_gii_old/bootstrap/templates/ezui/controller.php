<?php echo "<?php\n"; ?>
 class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?> {

	/**
	 * 首页列表.
	 */
	public function actionIndex() {
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$model = new <?php echo $this->modelClass; ?>('search');
			$model->unsetAttributes();  // 清理默认值
			if (isset($_GET['<?php echo $this->modelClass; ?>']))
				$model->attributes = $_GET['<?php echo $this->modelClass; ?>'];

			echo EZui::activeDataProvider($model->search(), true);
			Yii::app()->end();
		}

        $model = new <?php echo $this->modelClass; ?>;
		$this->render('index',array('model'=>$model));
	}

	/**
	 * 保存
	 */
	public function actionSave() {
		if (empty($_POST['<?php echo $this->modelClass; ?>']['id'])) {
			$model = new <?php echo $this->modelClass; ?>;
		} else {
			$model = $this->loadModel($_POST['<?php echo $this->modelClass; ?>']['id']);
		}

		// AJAX 表单验证
		$this->performAjaxValidation($model);
		if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
			$model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
			if ($model->save()) {
				$this->success(Yii::t('base', 'Saved Successfully'), null, $model->attributes);
			}
			$this->error(CHtml::errorSummary($model));
		}
		$this->error(Yii::t('base', 'Failed to save'));
	}

	/**
	 * 载入
	 */
	public function actionLoad($id) {
		$model = $this->loadModel($id);

		echo FlyEasyui::activeFormLoad($model);
	}

	/**
	 * 删除
	 * @param integer $id 主键
	 */
	public function actionDelete($id) {
		if (Yii::app()->request->isAjaxRequest) {
			$this->loadModel($id)->delete();
			$this->success(Yii::t('base', 'Deleted successfully'));
		}
		else
			$this->error(Yii::t('base', 'Illegal operation'));
	}

	/**
	 * 载入
	 * @param integer $id 主键
	 */
	public function loadModel($id) {
		$model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('base', 'The requested page does not exist'));
		return $model;
	}

	/**
	 * Ajax验证
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === '<?php echo $this->class2id($this->modelClass); ?>-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
