<?php
/* @var $this FileController */
/* @var $model File */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'file-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['enctype' => 'multipart/form-data'],
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="part">
	<h1>File from ERE</h1>
	<div class="row">
			<?php echo $form->labelEx($model,'make'); ?>
			<?php echo $form->dropDownList($model,'make',CHtml::listData(Make::model()->findAll(), 'code','name')); ?>
			<?php echo $form->error($model,'make'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'year'); ?>
		<?php echo $form->textField($model,'year'); ?>
		<?php echo $form->error($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'month'); ?>
		<?php echo $form->textField($model,'month'); ?>
		<?php echo $form->error($model,'month'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'valid_from'); ?>
		<?php	$this->Widget('zii.widgets.jui.CJuiDatePicker',array(
			'model'     => $model,
			'attribute' => "valid_from",
			'name'=>'datepicker1',
			'options'=>array(
				'dateFormat' => 'yy-mm-dd',
				'htmlOptions'=>array(
					'class'=>'datepicker'
				),
			),
		));
		?>
		<?php echo $form->error($model,'valid_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'valid_to'); ?>
		<?php	$this->Widget('zii.widgets.jui.CJuiDatePicker',array(
			'model'     => $model,
			'attribute' => "valid_to",
			'name'=>'datepicker',
			'options'=>array(
				'dateFormat' => 'yy-mm-dd',
				'htmlOptions'=>array(
					'class'=>'datepicker'
				),
			),
		));
		?>
	<?php echo $form->error($model,'valid_to'); ?>
	</div>

	<div class="row">The file has a header &nbsp; <?php echo $form->checkBox($model,'columnHeader', array('value'=>true, 'uncheckValue'=>false)); ?> </div>
	<br/>
	<?php echo $form->fileField($model,'csv_file'); ?>
	</div>

<?php //echo CHtml::button('next',  array('onclick'=>'js:$("#next").toggle();retun fals'));?>


	<div class="part" id="importer">
	<h1>File from Importer</h1>

	<div class="row">choose delimeter &nbsp;<?php echo $form->dropDownList($model,'delimeter',array(','=>',',';'=>';','\t'=>'space'), array('options' => array('2'=>array('selected'=>true)))); ?></div>
	<br/>
	<?php echo $form->labelEx($model,'oem'); ?>
	<?php echo $form->textField($model,'oem'); ?>

	<?php echo $form->labelEx($model,'oem_new'); ?>
	<?php echo $form->textField($model,'oem_new'); ?>
	<br/>
	<?php echo $form->labelEx($model,'price'); ?>
	<?php echo $form->textField($model,'price'); ?>
	<br/>
	<?php echo $form->labelEx($model,'price_new'); ?>
	<?php echo $form->textField($model,'price_new'); ?>
	<br/>
	<?php echo $form->fileField($model,'csv_file_importer'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->