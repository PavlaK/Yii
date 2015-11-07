<?php


$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Loans', 'url'=>array('loan/admin')),

);
?>

<h1>Following people borrowed <?php echo $model->book_name; ?></h1>
<?php echo $model->relatedReaders();  ?>
