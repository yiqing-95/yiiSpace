<?php echo "<?php\n"; ?>

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->layout='dwz';
		$this->render('index');
	}
	//以下两个action用于测试，可删除
	public function actionTest(){
		echo '/default/test OK';
	}
	public function actionCreate()
	{
		if (isset($_POST['test'])){
			if(!empty($_POST['test']))
				dwzHelper::ok("文章保存成功！<div>后面的参数参看dwz手册</div>");
			else
				dwzHelper::error('错误!不能为空！，<div>这里如果直接传入$model则报$model的验证错误</div>');
		}
		$this->render('create');
	}
}