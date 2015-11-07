<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property integer $id
 * @property string $country
 * @property integer $make
 * @property string $OEM
 * @property string $OEM_new
 * @property integer $price
 * @property integer $price_new
 * @property string $valid_from
 * @property string $valid_to
 * @property integer $month
 * @property integer $year
 */
class File extends CActiveRecord
{
	public $columnHeader;
	public $csv_file_importer;
	public $csv_file;
	public $oem;
	public $price;
	public $oem_new;
	public $price_new;
	public $delimeter;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('make, month, year, csv_file, csv_file_importer, oem, price', 'required'),
			array('make, price, price_new, month, year', 'numerical', 'integerOnly'=>true),
			array('country', 'length', 'max'=>3),
			array('OEM, OEM_new', 'length', 'max'=>20),
			array('valid_from, valid_to', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, country, make, OEM, OEM_new, price, price_new, valid_from, valid_to, month, year', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'country' => 'Country',
			'make' => 'Make',
			'OEM' => 'Oem',
			'OEM_new' => 'Oem New',
			'price' => 'Price',
			'price_new' => 'Price New',
			'valid_from' => 'Valid From',
			'valid_to' => 'Valid To',
			'month' => 'Month',
			'year' => 'Year',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function actionGetExportFile()
	{
		Yii::app()->request->sendFile('export.csv',Yii::app()->user->getState('export'));
		Yii::app()->user->clearState('export');
	}
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		if(isset($_REQUEST['File'])){
			$make = $_REQUEST['File']['make'];
			$year = $_REQUEST['File']['year'];
			$month = $_REQUEST['File']['month'];
		}
		else{
			$make = null;
			$year= null;
			$month = null;
		}

		$this->make = $make;
		$this->year = $year;
		$this->month = $month;


		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('make',$make,true);
		$criteria->compare('OEM',$this->OEM,true);
		$criteria->compare('OEM_new',$this->OEM_new,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('price_new',$this->price_new, true);
		$criteria->compare('valid_from',$this->valid_from,true);
		$criteria->compare('valid_to',$this->valid_to,true);
		$criteria->compare('month',$month,true);
		$criteria->compare('year',$year,true);



		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function deleteRecords()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('year = ' . $_POST['File']['year'], 'AND');
		$criteria->addCondition('make = ' . $_POST['File']['make'], 'AND');
		$criteria->addCondition('month = ' . $_POST['File']['month']);

		$deleteRecords = File::model()->deleteAll($criteria);
		return $deleteRecords;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return File the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
