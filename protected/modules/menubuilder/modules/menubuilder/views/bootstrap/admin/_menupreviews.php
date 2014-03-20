<div class="emb-menupreview">
    <?php
    /**
     * Render the menupreviews configured in MenubuilderModule::previewMenus
     */
    $legend = Yii::t('MenubuilderModule.messages', 'Preview menu');

    if (!empty($userRoles))
        $legend .= ' [R: ' . implode(',', $userRoles) . ']';

    if (!empty($scenarios))
        $legend .= ' [S: ' . implode(',', $scenarios) . ']';
    ?>

    <legend><?php echo $legend ?></legend>
    <div class="emb-menupreviews">

        <?php
        $previews = array();

        foreach ($this->getModule()->previewMenus as $view => $title)
        {
            $ok = $view == 'bootstrapnavbar' || $view == 'bootstrapmenu' ? Yii::app()->hasComponent('bootstrap') : true;
            if ($ok)
            {
                $output = $this->renderPartial('menupreviews' . DIRECTORY_SEPARATOR . '_' . $view,array('viewParams' => $viewParams),true);

                if (!empty($output))
                    $previews[$title] = $output;
            }
        }

        if (!empty($previews))
        {
            foreach ($previews as $title => $preview)
            {
                echo CHtml::openTag('div', array('class' => 'clear', 'style' => 'margin-bottom:2em;'));
                echo CHtml::tag('h5', array(), $title);
                echo $preview;
                echo '</div>';
            }
        }
        else
            echo Yii::t('MenubuilderModule.messages', 'No menu items visible');
        ?>
    </div>
</div>