<?php

class UserController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	function actionRegister() {
		$model = new User('register');

		// collect user input data
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];

			// validate user input and redirect to the previous page if valid
			$model->setAttributes(array(
				'datereg' => time(), //additional data you want to insert
				'lastlogin' => time() //additional

			));

			if($model->save())
			{
				//optional
				$login=new LoginForm;
				$login->username = $_POST['User']['username'];
				$login->password = $_POST['User']['password'];
				if($login->validate() && $login->login())
					$this->redirect('/pages/welcome');
			}

		}
		else
			// display the registration form
			$this->render('register',array('model'=>$model));
	}
	public function beforeSave() {

		if(parent::beforeSave() && $this->isNewRecord) {

			$this->password = md5($this->password);

		}

		return true;
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}