<?php
/* @var $this FileController */
/* @var $data File */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<b><?php echo CHtml::link('View',[$data->file]); ?></b>
	<?php echo CHtml::link('Download', array('File/download?file=' . $data->file)); ?>

	<br />


</div>