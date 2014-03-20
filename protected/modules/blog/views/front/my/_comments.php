<?php foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#{$comment->id}", $comment->getUrl($post), array(
		'class'=>'cid',
		'title'=>'Permalink to this comment',
	)); ?>

	<div class="author">
		<?php echo $comment->authorLink; ?> [
		<?php $this->widget('ext.ipaddress.Ipaddress',
            array(
                'ip' => $comment->ip,
            ));
		?>
		]says:
	</div>

	<div class="time">
		<?php echo date('F j, Y \a\t h:i a',$comment->created); ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->content)); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>

