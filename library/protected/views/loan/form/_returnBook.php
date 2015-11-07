<?php
/* @var $id int */
/* @var $model Student */
/* @var $form CActiveForm */
/* @var $this ApplicationController */

?>



<tr>
    <?php

    echo $form->hiddenField($model, "[$id]id");
    ?>
    <td>
        <?php echo $form->labelEx($model,'book_name'); ?>
        <?php echo $form->dropDownList($model, "[$id]book_id",CHtml::listData(Book::model()->findAll(), 'id','book_name')); ?>
    </td>

    <td>
        <?php echo $form->checkBox($model,"[$id]status", array('value'=>2, 'uncheckValue'=>1)); ?>

</tr>

