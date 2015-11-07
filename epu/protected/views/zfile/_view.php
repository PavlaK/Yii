<?php
/* @var $this FileController */
/* @var $data File */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country')); ?>:</b>
	<?php echo CHtml::encode($data->country); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('make')); ?>:</b>
	<?php echo CHtml::encode($data->make); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('OEM')); ?>:</b>
	<?php echo CHtml::encode($data->OEM); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('OEM_new')); ?>:</b>
	<?php echo CHtml::encode($data->OEM_new); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_new')); ?>:</b>
	<?php echo CHtml::encode($data->price_new); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('valid_from')); ?>:</b>
	<?php echo CHtml::encode($data->valid_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('valid_to')); ?>:</b>
	<?php echo CHtml::encode($data->valid_to); ?>
	<br />

	*/ ?>

</div>