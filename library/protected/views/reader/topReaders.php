<?php
/* @var $this LoanController */
/* @var $data Loan */
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->topReaders(),
	'filter'=>$model,
	'columns'=>array(

		'reader_name',

	),
));
?>
