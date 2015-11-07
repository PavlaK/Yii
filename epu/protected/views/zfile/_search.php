<?php
/* @var $this FileController */
/* @var $model File */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country'); ?>
		<?php echo $form->textField($model,'country'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'make'); ?>
		<?php echo $form->textField($model,'make'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OEM'); ?>
		<?php echo $form->textField($model,'OEM',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OEM_new'); ?>
		<?php echo $form->textField($model,'OEM_new',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_new'); ?>
		<?php echo $form->textField($model,'price_new'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'valid_from'); ?>
		<?php echo $form->textField($model,'valid_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'valid_to'); ?>
		<?php echo $form->textField($model,'valid_to'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->