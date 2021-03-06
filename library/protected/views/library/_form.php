<?php
/* @var $this LibraryController */
/* @var $model Library */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'library-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'book_id'); ?>
		<?php echo $form->dropDownList($model,'book_id',CHtml::listData(Book::model()->findAll(), 'id','book_name')); ?>
		<?php echo $form->error($model,'book_id'); ?>
	</div>

	<!--	<div class="row">-->
	<!--		--><?php //echo $form->labelEx($model,'copy'); ?>
	<!--		--><?php //echo $form->textField($model,'copy'); ?>
	<!--		--><?php //echo $form->error($model,'copy'); ?>
	<!--	</div>-->

	<div class="row">
		<?php echo $form->textField($model,'copyIn'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->