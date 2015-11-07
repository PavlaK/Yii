<?php
/* @var $this LoanController */
/* @var $model Loan */

$this->breadcrumbs=array(
	'Loans'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Loan', 'url'=>array('index')),
	array('label'=>'Create Loan', 'url'=>array('create')),
	array('label'=>'Update Loan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Loan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Loan', 'url'=>array('admin')),
);
?>

<h1>View Loan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'reader_id',
		'reader.reader_name',
		'borrow_date',
		'due_date',
	),
)); ?>
