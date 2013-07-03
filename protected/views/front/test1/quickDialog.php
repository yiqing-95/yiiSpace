<?php
$this->breadcrumbs=array(
	'Test',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>

    <?php
EQuickDlgs::contentButton(
    array(
        'content' => 'This is the help text', //$this->renderPartial('_help',array(),true),
        'dialogTitle' => 'Help',
        'dialogWidth' => 200,
        'dialogHeight' => 300,
        'openButtonText' => 'Help me',
        'closeButtonText' => 'Close', //comment to remove the close button from the dialog
    )
);
?>
