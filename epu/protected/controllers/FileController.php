<?php

class FileController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionEreUpload()
    {
        $model = new File;
        if (isset($_POST['File'])) {
            $model->deleteRecords();
            $model->attributes = $_POST['File'];
//            $make = $model->make;
            $month = $model->month;
            $year = $model->year;

            //upload the ere file
            $csvFile = CUploadedFile::getInstance($model, 'csv_file');
            $tempLoc = $csvFile->getTempName();

            $handle = fopen($tempLoc, "r");
            // declare beginning (fixed part) ot the INSERT STMT + VALUES
            $sql = "INSERT INTO file(country, make, OEM, OEM_new, price, price_new, valid_from, valid_to, month, year) VALUES";
            $valuesString = '';
            $columnHeader = $_POST['File']['columnHeader'];
            $counter = 0;
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                if ($columnHeader) {
                    $columnHeader = false;
                    continue;
                }
                $country = $data[0];
                $make = $data[1];
                $OEM = $data[2];
                $OEM_new = $data[3];
                $price = $data[4];
                $price_new = $data[5];
                $valid_from = $data[6];
                $valid_to = $data[7];
                $valuesString .= "('$country', '$make', '$OEM','$OEM_new', '$price', '$price_new', '$valid_from', '$valid_to', '$month', '$year'),";

                // if we reached a multiple of 100
                if ($counter > 0 && ($counter % 100) == 0) {

                    // we check the query for commas
                    $valuesString = substr($valuesString, 0, strlen($valuesString) - 1); // remove comma
                    Yii::app()->db->createCommand($sql . $valuesString)->execute();
                    // we start all over again
                    $valuesString = '';
                }
                $counter++;
            }

            // is there anything else left? insert the rest
            if (strlen($valuesString) > 0) {
                $valuesString = substr($valuesString, 0, strlen($valuesString) - 1); // remove comma
                Yii::app()->db->createCommand($sql . $valuesString)->execute();
            }

            // UPLOAD IMPORTER'S FILE
            $csvFile = CUploadedFile::getInstance($model, 'csv_file_importer');
            $tempLoc = $csvFile->getTempName();

            $handle2 = fopen($tempLoc, "r");

            //CLICK NEXT - SHOW ANOTHER PART OF THE FORM (IMPORTERS)
            //UPLOAD FILE IMPORTERS
            //CLICK SHOW FILE - ECHO CONTENT OF THE FILE (render partial)
            //SELECT THE COLUMNS (NUMBER OF THE INDEX)
            //CLICK IMPORT

            // declare beginning (fixed part) ot the INSERT STMT + VALUES
            $sqlQuery = "DROP TEMPORARY TABLE IF EXISTS tempoTable;
                        CREATE TEMPORARY TABLE tempoTable (OEM VARCHAR (30),OEM_new VARCHAR (30), price INT, price_new INT, make int, month int, year int);
                        INSERT INTO tempoTable(OEM, OEM_new,price,price_new,make, month, year) VALUES";
            $valueString = '';
            $updateQuery = "UPDATE file INNER JOIN tempoTable on tempoTable.OEM = file.OEM
                                SET file.price = tempoTable.price
                                WHERE tempoTable.OEM = file.OEM AND tempoTable.make = file.make AND tempoTable.year = file.year AND tempoTable.month = file.month";
            $count = 0;
            $delimiter = $_POST['File']['delimeter'];
            while (!feof($handle2)) {
                $line = fgets($handle2);
                $data2 = explode($delimiter, $line);

                $OEM = $data2[$_POST['File']['oem']-1];

                if ( !empty($_POST['File']['oem_new'])){
                    $OEM_new = $data2[$_POST['File']['oem_new']-1];}
                else {$OEM_new = null;}

                $price = $data2[$_POST['File']['price']-1];

                if ( !empty($_POST['File']['price_new'])){
                    $price_new = $data2[$_POST['File']['price_new']-1];}
                else {$price_new = null;}


                $valueString .= "('$OEM','$OEM_new', '$price','$price_new', '$make', '$month', '$year'),";

                // if we reached a multiple of 100
                if ($count > 0 && ($count % 100) == 0) {
                    // we check the query for commas
                    $valueString = substr($valueString, 0, strlen($valueString) - 1); // remove comma

                    Yii::app()->db->createCommand($sqlQuery . $valueString)->execute();
                    // we start all over again
                    $valueString = '';
                    Yii::app()->db->createCommand($updateQuery)->execute();
                }
                $count++;
            }
            // is there anything else left? insert the rest
            if (strlen($valueString) > 0) {
                $valueString = substr($valueString, 0, strlen($valueString) - 1); // remove comma
                Yii::app()->db->createCommand($sqlQuery . $valueString)->execute();
                Yii::app()->db->createCommand($updateQuery)->execute();

            }
//            echo 'success';
            $this->render('download', array('model' => $model));
        }

        $this->render('import', array('model' => $model));
    }

    public function actionDownload()
    {

        $model = new File('search');
//        $model->unsetAttributes();  // clear default values
//        if (isset($_GET['File']))
//            $model->attributes = $_GET['File'];

        $this->render('download', array(
            'model' => $model,
        ));
    }

    public function actionExport()
    {
        if(isset($_REQUEST['File'])){
            $make = $_REQUEST['File']['make'];
            $year = $_REQUEST['File']['year'];
            $month = $_REQUEST['File']['month'];
        }
        // deactivate the log stuff on the bottom of the screen
        foreach (Yii::app()->log->routes as $route)        {
            if ($route instanceof CWebLogRoute) {
                $route->enabled = false;
            }
        }


        $query = "SELECT country, make, OEM, OEM_new, price, price_new, valid_from, valid_to
                  FROM file
                  WHERE make = $make AND year = $year AND month = $month" ;

        try {
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=data.csv");
            $statement = Yii::app()->db->createCommand($query)->queryAll();

            //HEADER
            $content = 'COUNTRY;MAKE;OEM;OEM_NEW;PRICE;PRICE_NEW;VALID_FROM;VALID_TO;'. "\n";

                foreach ($statement as $rs) {
                    //CONTENT
                    $content .= $rs['country'] . ';';
                    $content .= $rs['make'] . ';';
                    $content .= $rs['OEM'] . ';';
                    $content .= $rs['OEM_new'] . ';';
                    $content .= $rs['price'] . ';';
                    $content .= $rs['price_new'] . ';';
                    $content .= $rs['valid_from'] . ';';
                    $content .= $rs['valid_to'] . ';';
                    $content .= "\n";
                }
            }
        catch
            (PDOException $e) {
                ($e->getMessage());
                exit;
            }
        echo $content;
        exit();

    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('File');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new File('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['File']))
            $model->attributes = $_GET['File'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return File the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = File::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param File $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'file-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
//$this->redirect(array('download', 'make' => $model->make, 'month' => $model->month, 'make' => $model->make))
}
