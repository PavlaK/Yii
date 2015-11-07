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

	<p Add Book	 class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'reader_id'); ?>
		<?php echo $form->DropDownList($model,'reader_id', CHtml::listData(Reader::model()->findAll(), 'id','reader_name')); ?>
		<?php echo $form->error($model,'reader_id'); ?>
	</div>


	<table >
		<thead>
		<tr>
			<td>
				<?php echo CHtml::link('Add Book', '', array('onClick' => 'addBook($(this))')); ?>
			</td>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($model->items as $id => $item) {
			$this->renderPartial('form/_bookRow', array('id' => $id, 'model' => $item, 'form' => $form, 'this' => $this), false, true);
		}
		?>
		</tbody>


	</table>
	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php	$this->Widget('zii.widgets.jui.CJuiDatePicker',array(
			'model'     => $model,
			'attribute' => "due_date",
			'name'=>'datepickerd_due_date',
			'options'=>array(
				'dateFormat' => 'yy-mm-dd',
				'changeMonth'=>true,
				'minDate' => date('Y-m-d'),
				'maxDate' => 'minDate + 30d',
				'htmlOptions'=>array(
					'class'=>'datepicker'
				),
			),
		));
		?>
	</div>

	<div class="row buttons">
		<?php $this->renderPartial('form/_bookjs', array( 'form' => $form, 'this' => $this,),false,true); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>



</div><!-- form -->
