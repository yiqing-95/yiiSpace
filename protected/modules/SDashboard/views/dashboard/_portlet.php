<?php if($data->active ==1):
		$pos = explode(',',$data->position);
		$left = $pos[1];
		$top = $pos[0];
		$size = explode(',',$data->size);
		$width = $size[0];
		$height = $size[1];
?>
<li id="<?=$data->id;?>" class="portlet-li" style="position:absolute; top:<?=$top;?>px; left:<?=$left;?>px; z-index: 50;">
	<div href="#" class="thumbnail well" rel="tooltip" data-title="Tooltip">
       <div class="portlettitlediv" > 
			<?=	CHtml::tag('span',array('name'=>'portlettitle'.$data->id,'class'=>'portlettitle'),$data->title);?>
		</div>
			<a href="#" class="maximize"><i class="icon-resize-full"  title="Maximize"></i></a>
			<a href="#" class="toggle"><i class="icon-resize-small"  title="Minimize"></i></a>
			<a href="#" class="hideportlet"><i class="icon-eye-close"  title="Deactivate"></i></a>
			<a href="<?=$data->id;?>" class="update_portlet"><i class="icon-edit"  title="Update"></i></a>
			<?php
			echo CHtml::link('<i class="icon-ban-circle"></i>',"#",array('class'=>'delete_portlet','name'=>$data->id, 'title'=>'Delete'));
			?>

		<div class="portletcontent"  style="width:<?=$width;?>px; height:<?=$height;?>px;">
			<div class="contentwrapper">
				<?= $data->BBCode2Html($data->content);?>
			</div>
			<?php
				if ( isset( $data->ajaxrequest) && $data->ajaxrequest !=="" ){
				echo CHtml::tag('div',array('id'=>'ajax'.$data->id));
				echo '<script type="text/javascript">';
				echo CHtml::ajax(array('url'=>$data->ajaxrequest,'update'=>'#ajax'.$data->id));
				echo '</script>';
				}
			
			?>
		</div>
	</div>
</li>
<?php endif;?>
