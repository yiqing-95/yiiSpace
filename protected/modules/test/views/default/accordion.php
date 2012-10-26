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
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>
<?php
$this->widget('test.extensions.accordion.AccordianWidget',
    array(
        'class' => 'list1 pad_bot1',
        'data' => array(
            "TAb Name" => array("Link Name" => "URL", "Link Name" => "Url"),
            "TAb Name2" => array("Link Name" => "URL", "Link Name" => "Url"),
            "TAb Name3" => array("Link Name" => "URL", "Link Name" => "Url"),
        )
    )
);