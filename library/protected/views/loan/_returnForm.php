<?php
/* @var $this LoanController */
/* @var $model Loan */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerCoreScript('jquery');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'loan-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'reader_id'); ?>
		<?php echo $form->DropDownList($model,'reader_id', CHtml::listData(Reader::model()->findAll(), 'id','reader_name')); ?>
		<?php echo $form->error($model,'reader_id'); ?>
	</div>


	<table >

		<tbody>
		<?php
		foreach ($model->items as $id => $item) {
			$this->renderPartial('form/_returnBook', array('id' => $id, 'model' => $item, 'form' => $form, 'this' => $this), false, true);
		}
		?>
		</tbody>

	</table>

	<div class="row buttons">
		<?php $this->renderPartial('form/_bookjs', array( 'form' => $form, 'this' => $this,),false,true); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'submit'); ?>
	</div>

	<?php $this->endWidget(); ?>



</div><!-- form -->
