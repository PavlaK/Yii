<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
 * @property integer $id
 * @property integer $book_id
 * @property string $return_date
 * @property integer $status
 */
class Item extends CActiveRecord
{
	public $book_name;
	public $loan_id;
	public $copy_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('book_id, status', 'numerical', 'integerOnly'=>true),
			array('return_date', 'safe'),
			array('status', 'in', 'range'=>array(1,2)),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, book_id, return_date, status, copy, copy_id, book_name', 'safe', 'on'=>'search'),
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
//			'library' => array(self::BELONGS_TO, 'Library', 'book_id'),
			'copy' => array(self::BELONGS_TO, 'Library', 'copy_id'),
			'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
			'statuses' => array(self::BELONGS_TO, 'Status', 'status'),
			'loans' => array(self::MANY_MANY, 'Loan', 'loan_item(loan_id, item_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'book_id' => 'Book',
			'return_date' => 'Return Date',
			'status' => 'Status',
			'loan_id' => 'Loan ID'
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
	public function RelateLoanId ()
	{
		foreach ($this->loans as $loan) {

			$loan_id = $loan->id;

		}
		return  $loan_id;
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with=array(
			'book',
			'loans' => array(
				'together' => true,

			),
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.book_id',$this->book_id);
//		$criteria->compare('book.book_name',$this->book_name);
		$criteria->compare('t.return_date',$this->return_date,true);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function  beforeSave() {

		if ($this->status == 2) {
			$this->return_date = date('Y-m-d');
		}
		else $this->status = 1;


		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function bookNameLink()
	{
		return  CHtml::link($this->book_name, Yii::app()->createUrl("book/relatedReaders/",array("id"=>$this->book.id)));
	}
}
