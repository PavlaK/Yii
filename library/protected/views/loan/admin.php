<?php
/* @var $this LoanController */
/* @var $model Loan */

$this->breadcrumbs=array(
	'Loans'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Loan', 'url'=>array('index')),
	array('label'=>'Create Loan', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#loan-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Loans</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'loan-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

	'columns'=>array(
		'id',
		array(
			'name'=>'reader_id',
			'value' =>'$data->reader_id',
			'type'  => 'raw',
		),

		array(
			'name'=>'reader_name',
//			'value'=>'$data->readerNameLink()',
//			'value'=> 'Reader::model()->readerNameLink($data->id)',
// 				'value'=>'Reader::model()->findByPk($data->id)->readerNameLink()',

			'value' => 'CHtml::link($data->reader->reader_name, Yii::app()
                 ->createUrl("reader/readersLoans/",array("id"=>$data->reader_id)))',
			'type'  => 'raw',
		),

		array(
			'name'=>'book_name',
			'value'=>'$data->RelateBookName()',
			'type'  => 'raw',
		),

//		'borrow_date',
		'due_date',
		'status',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
