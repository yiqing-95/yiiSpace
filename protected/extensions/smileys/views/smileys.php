<!--修改了addSmiley，加了一个参数-->
<div class="<?php echo $containerCssClass;?>">
    <?php $i = 0; foreach ($smileys AS $smiley => $smileyDetail): ++$i; ?>
    <?php
    $image = $assetsUrl . '/groups/' . $group . '/';
    $image .= !empty($smileyDetail[0]) ? $smileyDetail[0] : '';
    $width = !empty($smileyDetail[1]) ? (int)$smileyDetail[1] : '';
    $height = !empty($smileyDetail[2]) ? (int)$smileyDetail[2] : '';
    $title = $alt = !empty($smileyDetail[3]) ? CHtml::encode($smileyDetail[3]) : '';
    ?>
    <div class="<?php echo $wrapperCssClass;?>" style="width:<?php echo $width;?>px;height:<?php echo $height;?>px">
        <a href="javascript:;" onclick="addSmiley('<?php echo CHtml::encode($smiley);?>','<?php echo $textareaId;?>','<?php echo $wrapperCssClass.'Btn';?>');">
            <img src="<?php echo $image;?>" width="<?php echo $width;?>" height="<?php echo $height;?>"
                 alt="<?php echo $alt;?>" title="<?php echo $title;?>"/>
        </a>
    </div>
    <?php if ($perRow > 0 && $i % $perRow == 0): ?>
        <div class="clear">&nbsp;</div><?php endif; ?>
    <?php endforeach;?>
</div>