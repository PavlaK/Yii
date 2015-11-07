<?php
/* @var $this FileController */
/* @var $model File */


/**
 * get values from B to C
 * if I have GET stuff I use it later in the code
 *
 * start form
 *
 * here i use the GET stuff IF i have it, or some defaults can apply
 *
 * here I display the grid of prices, with teh defaults (or GET stuff if any)
 *
 * Beneath I place a submit bottun
 * $model->make = 'default'
 * end form
 *
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array('id'=>'file-form',));
// * if I have GET stuff I use it later in the code
//if(isset($_REQUEST['File'])){
//	$make = $_REQUEST['File']['make'];
//	$year = $_REQUEST['File']['year'];
//	$month = $_REQUEST['File']['month'];
//}
//else{
//	$make = null;
//	$year= null;
//	$month = null;
//}
// ?>
<!---->
<?php
//	$model->make = $make;
//	$model->year = $year;
//	$model->month = $month;


$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'country',
		'make',
		'OEM',
		'OEM_new',
		'price',
		'price_new',
		'valid_from',
		'valid_to',
		'month',
		'year'
	),


));
?>

<?php echo CHtml::submitButton('export',array('submit' => array('file/export'))); ?>

<?php $this->endWidget(); ?>

