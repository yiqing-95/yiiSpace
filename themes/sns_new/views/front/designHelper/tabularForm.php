<pre>
    <table>
        <tbody>
         <?php  foreach($columns  as $column): ?>
    <tr>
                <th width="220" class="alR">
                  <?php echo  " <?php echo \$form->labelEx(\$model,'[]{$column}'); ?>:"; ?>
                </th>
                <td>
                  <?php echo  "<?php echo \$form->textField(\$model, '[]{$column}', array('maxlength' => 50,'class'=>'w250')); ?>\n"; ?>
                  <?php echo  "<?php echo \$form->error(\$model,'[]{$column}'); ?>\n" ; ?>
                </td>
            </tr><!-- tr -->
         <?php endforeach;  ?>
        </tbody>
    </table>
  <div class="btn"><?php echo "<?php echo GxHtml::submitButton(Yii::t('app', 'Save'));?>"; ?></div>
</pre>
