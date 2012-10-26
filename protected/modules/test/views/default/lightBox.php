<?php
$this->breadcrumbs = array(
    $this->module->id,
);
?>
<h1><?php echo $this->uniqueId . '/' . $this->action->id; ?></h1>

<p>
    This is the view content for action "<?php echo $this->action->id; ?>".
    The action belongs to the controller "<?php echo get_class($this); ?>"
    in the "<?php echo $this->module->id; ?>" module.
</p>
<p id="test" class="lightBox">
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>
<?php
$content = 'hello world ';

$this->widget('test.extensions.ELightBoxContentWidget.ELightBoxContentWidget',
    array(
        'classname'=>'lightBox',// " class to be applied over a link to make it accessable in jquery" ,
        'divid'   =>'test',//" Id which will be assigned to div which will show conent",
        'width'   => '345px',//'width of the div',
        'content' => $content,// content variable will carry html data to be displayed
        'linklabel'=>" label of the link which you have to display over your page"
    ) );

?>