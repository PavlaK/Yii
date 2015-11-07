<?php
/* @var $this LoanController */
/* @var $model Loan */

$this->breadcrumbs=array(
	'Loans'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Loan', 'url'=>array('index')),
	array('label'=>'Create Loan', 'url'=>array('create')),
	array('label'=>'View Loan', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Loan', 'url'=>array('admin')),
);
?>

<h1>Update Loan <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_returnForm', array('model'=>$model)); ?>