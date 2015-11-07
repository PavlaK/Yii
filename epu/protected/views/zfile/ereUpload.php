<?php
/* @var $this FileController */
/* @var $model File */

$this->breadcrumbs=array(
	'Files'=>array('index'),
	'Create',
);

?>

<h1>Import files</h1>

<?php $this->renderPartial('_ereUploadForm', array('model'=>$model)); ?>