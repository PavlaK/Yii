<?php
/* @var $this FileController */
/* @var $model File */

$this->breadcrumbs=array(
	'Files'=>array('index'),
	'Create',
);

?>



<?php $this->renderPartial('_form', array('model'=>$model)); ?>