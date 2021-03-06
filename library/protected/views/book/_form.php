<?php
/* @var $this BookController */
/* @var $model Book */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
<!--	--><?php
//	echo $form->hiddenField($model, 'Book[book_id]');
//	?>
	<div class="row">
		<?php echo $form->labelEx($model,'book_name'); ?>
		<?php echo $form->textField($model,'book_name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'book_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'author_id'); ?>
		<?php echo CHtml::dropDownList('Book[author_id]', @$_GET['Book']['author_id'], CHtml::listData(Author::model()->findAll(),'id','author_name'), ['multiple'=>1]) ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo CHtml::dropDownList('Book[category_id]', @$_GET['Book']['category_id'], CHtml::listData(Category::model()->findAll(),'id','category_name'), ['multiple'=>1]) ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'publisher_id'); ?>
		<?php echo $form->DropDownList($model,'publisher_id', CHtml::listData(Publisher::model()->findAll(), 'id','publisher_name')); ?>
		<?php echo $form->error($model,'publisher_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'copy'); ?>
		<?php echo $form->textField($model,'copy'); ?>
		<?php echo $form->error($model,'copy'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->