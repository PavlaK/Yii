<?php
/* @var $this LoanController */
/* @var $data Loan */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>

	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reader_id')); ?>:</b>
	<?php echo CHtml::encode($data->reader_id); ?>

	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('reader_name')); ?>:</b>
	<?php echo CHtml::link($data->reader->reader_name, Yii::app()
		->createUrl("reader/readersLoans/",array("id"=>$data->reader_id))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('borrow_date')); ?>:</b>
	<?php echo CHtml::encode($data->borrow_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('due_date')); ?>:</b>
	<?php echo CHtml::encode($data->due_date); ?>
	<br />



</div>