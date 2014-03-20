<table width="100" height="90" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center">
            <?php if($data['is_dir']) : ?>
               <a href="<?php echo $this->createUrl('',array('currDir'=>urlencode($data['path']))); ?>">
            <?php endif;  ?>

            <img src="<?php echo Registry::instance()->get('assetsUrl') ; ?>/images/fileType/ico48/<?php echo $data['ico_type']; ?>.png"
                 id="<?php echo $data['path'] ; ?>"
                 filename="<?php echo $data['name'];  ?>"
                 filepath="<?php echo $data['path'];  ?>"
                 class ="<?php echo $data['class'];  ?>  resizeme"
                 onLoad="zoomImg(this)"
                 parent ="<?php echo $data['parent'];  ?>"
                />
            <?php if($data['is_dir']) : ?>
                </a>
             <?php endif;  ?>
            <span><?php echo $data['name'];  ?></span>
            <span><?php echo $data['ctime'];  ?></span>
        </td>
    </tr>
</table>