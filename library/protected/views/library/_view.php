<?php
/* @var $this LibraryController */
/* @var $data Library */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_id')); ?>:</b>
	<?php echo CHtml::encode($data->book_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('introduction_date')); ?>:</b>
	<?php echo CHtml::encode($data->introduction_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('copy')); ?>:</b>
	<?php echo CHtml::encode($data->copy); ?>
	<br />


</div>