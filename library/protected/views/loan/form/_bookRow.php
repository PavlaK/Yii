<?php
/* @var $id int */
/* @var $model Student */
/* @var $form CActiveForm */
/* @var $this ApplicationController */

?>

<tr>
    <?php echo $form->hiddenField($model, "[$id]id");
    ?>
    <?php echo $form->hiddenField($model, "[$id]copy");    ?>
    <td>
        <?php echo $form->labelEx($model,'book_name'); ?>
        <?php
        echo $form->dropDownList($model, "[$id]book_id",
            CHtml::listData(Book::model()->findAvailableBooks(), 'id','book_name')) ; ?>
    </td>
        <?php echo CHtml::link('Delete', '#', array('onClick' => 'deleteBook($(this));return false;')); ?>
    </td>
</tr>

