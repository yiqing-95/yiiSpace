
<div data-options="region:'west',border:false" style="width: 450px">
	<table id="<?php echo $this->class2id($this->modelClass); ?>-grid" style="border-width: 0 1px 0 0;">
		<thead>
			<tr>
			<?php
					$count=0;
					foreach($this->tableSchema->columns as $column):
				?>
				<th data-options="field:'<?php echo $column->name?>'">
					<?php echo "<?php echo \$model->getAttributeLabel('".$column->name."') ?>"?>
				</th>
				<?php
					endforeach;
				?>
			</tr>

		</thead>
	</table>
</div>
<div data-options="region:'center',border:false">
	<?php echo "<?php \$this->renderPartial('_form', array('model' => \$model)); ?>"?>
</div>
<div id="<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar">

	<a href="#" class="easyui-linkbutton fn-left" action="add" data-options="iconCls:'icon-add',plain:true"><?php echo "<?php echo Yii::t('base', 'Add') ?>"?></a>
	<a href="#" class="easyui-linkbutton fn-left" action="edit" data-options="iconCls:'icon-edit',plain:true,disabled:true"><?php echo "<?php echo Yii::t('base', 'Edit') ?>"; ?></a>
	<div class="dialog-tool-separator"></div>
	<a href="#" class="easyui-linkbutton fn-left" action="delete" data-options="iconCls:'icon-remove',plain:true,disabled:true"><?php echo "<?php echo Yii::t('base', 'Delete') ?>"?></a>
</div>
<script language="javascript">
	
	//格式化显示
	

	//表单
	//载入
	function load_<?php echo $this->modelClass; ?>_form(id) {
		$.getJSON('<?php echo "<?php echo \$this->createUrl('load') ?>"?>',
		{id:id},
		function(data) {
			$('#<?php echo $this->class2id($this->modelClass); ?>-form').form('load', data);

		});
	}
	//重置表单
	function reset_<?php echo $this->modelClass; ?>_form() {

		var id = $('<?php echo $this->modelClass; ?>_id').val();

		if(id != '')
			load_<?php echo $this->modelClass; ?>_form(id);
		else {
			$('#<?php echo $this->class2id($this->modelClass); ?>-form').form('clear');
		}

	}

	//提交表单
	function <?php echo $this->class2id($this->modelClass); ?>_form_afterValidate(form, data, hasError) {

		if(hasError == false) {
			var id = $('<?php echo $this->modelClass; ?>_id').val();
			$('#<?php echo $this->class2id($this->modelClass); ?>-form').ajaxSubmit({
				success:function(data){
					if(data.status) {

						$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('options').selectId = data.data.id;
						if(id == '')
							$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('load');
						else
							$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('reload');

						load_<?php echo $this->modelClass; ?>_form(data.data.id);
						$.messager.show({
							title:'<?php echo "<?php echo Yii::t('base', 'Operation tips'); ?>";?>',
							msg: data.info,
							showType:'show'
						});
					} else {
						$.messager.alert('<?php echo "<?php echo Yii::t('base', 'Operation failed'); ?>"?>',data.info,'error');
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$.messager.alert('<?php echo "<?php echo Yii::t('base', 'Operation failed'); ?>"?>',XMLHttpRequest.status+":"+XMLHttpRequest.responseText,'error');
				},
				dataType: 'json'
			});
		}
		return false;
	}
	//GRID配置
	var gridOptions = {
		url: '<?php echo "<?php echo \$this->createUrl('index') ?>"?>',
		idField: 'id',
		rownumbers: true,
		fitColumns: true,
		autoRowHeight: false,
		pageSize: 20,
		pageList: [10,15,20,25,30,40,50],
		pagination:true,
		sortName: 'id',
		sortOrder: 'DESC',
		fit: true,
		singleSelect: true,
		selectId: 0,
		toolbar: '#<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar',
		onLoadSuccess: function() {
			var selectId = $(this).datagrid('options').selectId;

			if(selectId !=0) {
				$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('selectRecord', selectId);
			} else {
				$('#<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar [action="edit"]').linkbutton('disable');
				$('#<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar [action="delete"]').linkbutton('disable');
			}
		},
		onDblClickRow:function(rowIndex,rowData) {
			load_<?php echo $this->modelClass; ?>_form(rowData.id);
		},
		onClickRow: function(rowIndex,rowData) {
			$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('options').selectId =rowData.id;
			$('#<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar [action="edit"]').linkbutton('enable');
			$('#<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar [action="delete"]').linkbutton('enable');
		}
	};
	$(document).ready(function(){
		//初始化表格
		$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid(gridOptions);
		//菜单操作
		$('#<?php echo $this->class2id($this->modelClass); ?>-grid-toolbar a').click(function(){
			
			if($(this).linkbutton('options').disabled)
				return ;
			var action = $(this).attr('action');
			var rowData = $('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('getSelected');
			if (action == 'add') {
				$('#<?php echo $this->class2id($this->modelClass); ?>-form').form('clear');
				$('#<?php echo $this->class2id($this->modelClass); ?>-form').form('load', {'AdminUser[disabled]':0,'AdminUser[role_id]':""});

			} else if(action == 'edit') {
				load_role_form(rowData.id);
			} else if(action == 'delete') {
				$.messager.confirm('<?php echo "<?php echo Yii::t('base', 'Operation tips'); ?>"?>',
				'<?php echo "<?php echo Yii::t('zii', 'Are you sure you want to delete this item?') ?>"?>',
				function(r){
					if (r){
						$.getJSON('<?php echo "<?php echo \$this->createUrl('delete') ?>"?>',
						{id:rowData.id},
						function(data) {
							if(data) {
								if(data.status == 1) {
									if($('<?php echo $this->modelClass; ?>_id').val() == rowData.id) {
										$('<?php echo $this->modelClass; ?>_id').val('');
										reset_<?php echo $this->modelClass; ?>_form();
									}
									$('#<?php echo $this->class2id($this->modelClass); ?>-grid').datagrid('reload');
									$.messager.show({
										title:'<?php echo "<?php echo Yii::t('base', 'Operation tips'); ?>"?>',
										msg: data.info,
										showType:'show'
									});
								} else if(data.status == 0) {
									$.messager.alert(
									'<?php echo "<?php echo Yii::t('base', 'Operation tips'); ?>"?>',
									data.info,
									'error'
								);
								} else if(data.status == 2) {

									$.messager.alert(
									'<?php echo "<?php echo Yii::t('base', 'Operation warning'); ?>"?>',
									data.info,
									'warning'
								);
								}
							}
						});
					}
				});
			}

		});
		//重置表单

		reset_<?php echo $this->modelClass; ?>_form();
		$('#<?php echo $this->class2id($this->modelClass); ?>-form .form-actions  [action="reset"]').click(function(){
			reset_<?php echo $this->modelClass; ?>_form();
		});
	});
</script>