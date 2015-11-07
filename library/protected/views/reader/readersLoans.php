
<?php
/* @var $this ReaderController */
///* @var $model Reader */

$this->breadcrumbs=array(
	'Readers'=>array('index'),
	$model->id,
);

$this->menu=array(
    array('label'=>'Loans', 'url'=>array('loan/admin')),
);
?>

<h1> <?php echo $model->reader_name . ' borrowed following books'; ?></h1>
<?php echo $model->RelatedBookName();  ?>




<?php //$this->widget('zii.widgets.grid.CGridView', array(
//    'dataProvider'=>$model->search(),
//    'filter'=>$model,
//   	'columns'=>array(
//        array(
//		'name'=>'book_name',
//		'value'=>'CHtml::link($data->RelatedBookName(), Yii::app()
//                 ->createUrl("book/relatedReaders/",array("id"=>$data->id)))',
//		'type'  => 'raw',
//	),
//
//        ),
//)); ?>
