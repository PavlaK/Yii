
<?php

/* @var $this LoanController */
/* @var $model Loan */
?>


<?php



$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'student-grid',
	'dataProvider'=>$model->topBooks(),
	'filter'=>$model,
	'columns'=>array(

		array(
			'header'=>'Book name',
			'value'=> function($data) {
				return Book::model()->findByPk($data["id"])->bookNameLink();
				},
//			'value'=>'CHtml::link($data["book_name"], Yii::app()->createUrl("book/relatedReaders/",array("id"=>$data["id"])))',
			'type'  => 'raw'
		),
		array(
			'header'=>'Total',
			'value'=>'$data["total"]',
		),
	),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'student-grid',
	'dataProvider'=>$model->topReaders(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'value'=> function($data) {
				return Reader::model()->findByPk($data["id"])->readerNameLink();
				},
//			'value'=>'CHtml::link($data["reader_name"], Yii::app()->createUrl("reader/readersLoans/",array("id"=>$data["id"])))',
			'type'  => 'raw',
		),
		array(
			'header'=>'Total',
			'value'=>'$data["totalCount"]',
		),
	),
)); ?>