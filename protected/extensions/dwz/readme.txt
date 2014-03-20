在线演示地址 http://demo.dwzjs.com
DWZ框架使用手册 http://demo.dwzjs.com/doc/dwz-user-guide.pdf
Ajax开发视频教材 http://demo.dwzjs.com/doc/dwz-ajax-develop.swf
DWZ框架演示视频 http://demo.dwzjs.com/doc/dwz-user-guide.swf
----------------------------------------------------------------------
Dwz for Yii使用说明：
1、复制dwz到应用的ext目录下。并在config/main.php中配置
	'modules'=>array(
		'admin',
		'gii'=>array(
			'class'=>'system.gii.giiModule',
			'password'=>'admin',
			'generatorPaths'=>array(
				'ext.dwz.gii', //可以继续配置其他路径
            ),
		),
	),
2、打开gii新建module，例子用admin，在模板一栏选dwz。生成admin模块。
3、像往常一样使用gii生成crud，只要在注意模板选择dwz即可


已完成项：
1、Dwz初始化基类 DwzWidget。
2、navTab结构挂件 DwzNavTab。
3、手风琴挂件 DwzAccordion。
4、tabs挂件 DwzTabs。
5、panel挂件 DwzPanel
6、Grid挂件 DwzGrid
7、GridView挂件 DwzGridView
7、js分页组件 DwzPager

注：DwzTable适合之列表数据用，DwzGrid由于DWZ的表格限制目前还是半成品(因为没有过滤和排序，dwz作者说要重写这个table，所以等吧)，DwzGridview功能和CgridView完全相同，只是固定了分页栏；
所以目前功能上DwzTalbe < DwzGrid < DwzGridView  （^_^请不要头晕)
之所以要做DwzTable和DwzGrid只是想界面和DWZ相融合一点。

DWZ部分使用实例：
1、ajax：ajax获取一个内容替换rel指定容器内的内容
    CHtml::link('ajax页面',url,array('target'=>'ajax','rel'=>'elementId'))

2、navTabTodo：ajax Post返回的内容为JSON格式，参考DWZ文档的“普通“Ajax表单提交”部分
    CHtml::link('text',url,array('target'=>'navTabTodo','title'=>'message'))

3、dialog：一个弹出框 全屏用'max'=>ture，有遮罩层用'mask'=>true,
    CHtml::link('弹出窗中打开',array('/admin/default/test'),array('target'=>'dialog','rel'=>'窗口标识','title'=>'[自定义标题]','width'=>800, 'height'=>600));

4、navTab：在navTab中打开一个标签，要使用，页面中必须先有navTab的结构
    CHtml::link('在navTab中打开',url,array('target'=>'navTab','rel'=>'tab标识号,相同标识号会覆盖之前的'))

5、容器高度自适应。
    <div class="page">
      <div class="pageContent">
          <div class="pageFormContent" layoutH="56">自适应高度区<div>
          <div class="formBar">工具栏区</div>
      </div>
      <div class="panelBar">
        <div class="row buttons">
          <?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
        </div>
      </div>
    </div>
    
6、Input Alt：文本框加提示
    CHtml::textField('name', '', array('alt'=>'测试input alt扩展')); 

7、日历控件：不好用
    CHtml::textField('name', '', array('class'=>'date','[pattern'=>'yyyy-mm-dd'.']','[yearstart'=>'-80'.']','[yearend'=>'5'.']')); 

8、tree：直接使用Cmenu来来生成，很灵活如下：
    <?php $this->widget('zii.widgets.CMenu',array(
      'activateParents'=>true,
      'htmlOptions'=>array('class'=>'tree treeFolder '),
      'items'=>array(
        array('label'=>'文章管理', 'url'=>array('/admin/articles/admin'),'items'=>array(
          array('label'=>'创建文章', 'url'=>array('/admin/articles/create'), 'linkOptions'=>array('target'=>'navTab','rel'=>'page1')),
          array('label'=>'文章列表', 'url'=>array('/admin/articles/index'), 'linkOptions'=>array('target'=>'navTab','rel'=>'page2')),
          array('label'=>'管理文章', 'url'=>array('/admin/articles/admin'), 'linkOptions'=>array('target'=>'navTab','rel'=>'page2')),
        )),
        array('label'=>'栏目管理', 'url'=>array('/admin/ArtCategory/'), 'linkOptions'=>array('target'=>'navTab','rel'=>'page5')),
      ),
    )); ?>
