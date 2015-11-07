<?php
/* @var $this BookController */
/* @var $data Book */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_name')); ?>:</b>
	<?php echo CHtml::link($data->bookNameLink(), array("id"=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author_name')); ?>:</b>
	<?php echo  CHtml::encode(implode(',',  CHtml::listData($data->authors,'id','author_name'))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_name')); ?>:</b>
	<?php echo  CHtml::encode(implode(',',  CHtml::listData($data->category,'id','category_name'))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<?php echo CHtml::encode($data->publisher->publisher_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('copy')); ?>:</b>
	<?php echo CHtml::encode($data->copy); ?>
	<br />


</div>