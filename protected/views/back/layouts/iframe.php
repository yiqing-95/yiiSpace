<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <title>
        <?php
        if(count($this->menuLabelList) >1){
            $curMenuLabel = end($this->menuLabelList);
            echo $curMenuLabel;
        }
        ?>
    </title>
</head>
<body>
<div class="row">
    <div class="row">
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'homeLink' => false,
            'links' => array_merge($this->menuLabelList, $this->breadcrumbs),
            'htmlOptions'=>array(
                'class'=>'seven columns'
            )
        )); ?><!-- breadcrumbs -->
            <span class="one columns">
                <a href="#" onclick="history.go(-1);">回退</a>
            </span>
    </div>

    <div class="row">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'id' => 'menuItems',
            'htmlOptions' => array(
                'class' => 'alert alert-success',// alert-error,alert-block ,alert-info ,alert-success
            )
        ));
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'items' => $this->menu
        ));

        $this->endWidget();
        ?>
    </div>

</div>
    <div class="container">
        <?php echo $content; ?>
    </div>

</body>
</html>